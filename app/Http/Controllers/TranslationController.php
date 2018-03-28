<?php

namespace App\Http\Controllers;


use App\Translation;
use App\Source;
use App\Project;
use App\Language;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class TranslationController extends Controller
{
    public function index(Request $request)
    {
        $projects = Project::all()->load('sources');
        
        if (isset($request->language_id))
        { 
            session([ 'language_id' => $request->language_id ]);
        }
        elseif(!session()->has('language_id'))
        {
            session([ 'language_id' => 'en-US' ]);
        }
        
        if (isset($request->project_id))
        { 
            session([ 'project_id' => $request->project_id ]);
        }
        elseif(!session()->has('project_id') && !$projects->isEmpty())
        {
            session([ 'project_id' => $projects->first()->id ]);
        }
        
        $projects = Project::all()->load('sources');
        $languages = Language::all();
        $translations = Translation::all()->load('source');
        
        $sources = Source::all()->load([
            'translations'=>function ($query) {
                $query->where('language_id', session()->get('language_id'))
                    ->where('project_id', session()->get('project_id'));
            }
        ]);
        
        return view('translations.index')->with(['projects'=>$projects, 'languages'=>$languages, 'translations'=>$translations, 'sources'=>$sources]);   
    }
    
    
    public function export()
    {
        $projects = Project::all();
        $languages = Language::all();
        return view('export.index')->with(['projects'=>$projects, 'languages'=>$languages]);   
    }
    
    public function exportAction( Request $request )
    {
        if (isset($request->language_id))
        { 
            session([ 'language_id' => $request->language_id ]);
        }
        
        if (isset($request->project_id))
        { 
            session([ 'project_id' => $request->project_id ]);
        }
       
        $project = Project::find($request->project_id);
        $language_id = $request->language_id;
        $data = [];
        
        $sources = Source::where('project_id', $project->id)->get()->load([
            'translations'=>function ($query) {
                $query->where('language_id', session()->get('language_id'));
            }
        ]);
        
        foreach($sources as $key => $source)
        {
            $data[$key][] = $source->id;
            
            if($language_id == "en-US")
            {
                $data[$key][] = $source->key;
            }
            else
            {
                $en_translation = Translation::where('source_id', $source->id)->where('language_id', 'en-US')->first();
                
                if($en_translation)
                {
                    $data[$key][] = $en_translation->translation;
                }
                else
                {
                    $data[$key][] = $source->key;
                }
            }
            
            foreach($source->translations as $t_key => $translation)
            {
                $data[$key][] = $translation->translation;
            }
        }
        
        $path = storage_path('app/public/translations/');
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet()->fromArray(
            $data,  
            NULL       
        );
        
        $writer = new Xlsx($spreadsheet);
        
        $timestamp = Carbon::now();
        
        $writer->save($path.$project->name.'_'.$language_id.'_'.$timestamp.'.xlsx');
        
        return redirect()->route('export');
    }
    
    public function findOrCreate(Request $request)
    {
        $source = Source::find($request->source_id);
            
        if ($request->has('translation_id')) 
        {
            $translation = Translation::find($request->translation_id); 
            $translation->translation = $request->translation;
            
            $translation->save();
        }
        else
        {
            $translation = new Translation;
            $translation->source_id = $request->source_id;
            $translation->translation = $request->translation;
            $translation->language_id = session()->get('language_id');
            $translation->project_id = $source->project_id;
            $translation->save(); 
        }
        
        return redirect()->back();   
    }
}
