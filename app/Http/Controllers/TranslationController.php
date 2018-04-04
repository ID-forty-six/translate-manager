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
use Storage;
use Session;


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
       
        $language_id = $request->language_id;
        $data = [];
        
        $sources = Source::all()->load([
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
                $en_translation = Translation::where('source_id', $source->id)->where('language_id', 'en-US')->where('translation', '!=', null)->first();
                
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
        
        
        $sheet = $spreadsheet->getActiveSheet();
        
        //Create header row
        $headers = ['id', 'key', session()->get('language_id')];
        array_unshift($data, $headers);
        
        // populate excel file
        $sheet->fromArray(
            $data,  
            NULL       
        );
        
        $writer = new Xlsx($spreadsheet);
        
        $timestamp = Carbon::now();
        
        $writer->save($path.'_'.$language_id.'_'.$timestamp.'.xlsx');
        
        Session::flash('message', "File $language_id $timestamp.xlsx exported to $path");
        
        return redirect()->route('export');
    }
    
    public function findOrCreate(Request $request)
    {
        $source = Source::find($request->source_id);
            
        if ($request->has('translation_id')) 
        {
            $translation = Translation::find($request->translation_id); 
            $translation->translation = $request->translation;
            $translation->is_published = 0;
            
            $translation->save();
        }
        else
        {
            $translation = new Translation;
            $translation->source_id = $request->source_id;
            $translation->translation = $request->translation;
            $translation->language_id = session()->get('language_id');
            $translation->project_id = $source->project_id;
            $translation->is_published = 0;
            $translation->save(); 
        }
        
        return redirect()->back();   
    }
    
    public function import()
    {
        $projects = Project::all();
        $languages = Language::all();
        return view('import.index')->with(['projects'=>$projects, 'languages'=>$languages]);   
    }
    
    public function importAction( Request $request )
    {
        
        if (isset($request->language_id))
        { 
            session([ 'language_id' => $request->language_id ]);
        }
        
        if (isset($request->project_id))
        { 
            session([ 'project_id' => $request->project_id ]);
        }
        
        $language_id = session()->get('language_id');
        $project_id = session()->get('project_id');
        
        //TODO perkelti i use
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($request->file('upload'));
        
        $translations_array = $spreadsheet->getActiveSheet()->toArray();
        
        // skip header row
        array_shift( $translations_array );
    
        $sources = array();
        
        /*$translations = Translation::where('project_id',$project_id)
                ->where('language_id', $language_id)
                ->get();*/
        
        $new_count = 0;
        $update_count = 0;
        $import_errors = [];
        
        foreach ($translations_array as $key=>$item) 
        {
            $row = $key + 1;
            if($item[0] == null || $item[1] == null)
            {
                $import_errors[] = "ERROR - import file, row($row): missing source id or key";
                continue;
            }
                
            $source = Source::find($item[0]);
            
            if(!$source)
            {
                $import_errors[] = "ERROR - import file, row($row): source(id=$item[0]) does not exist!";
                continue;
            }
            
            $translation = Translation::where('source_id', $source->id)
                ->where('project_id', $project_id)
                ->where('language_id', $language_id)
                ->first();
            
            // tikrinam ar sutampa ID ir keys
            if( $item[1] != $source->key)
            {
                $en_translation = Translation::where('source_id', $source->id)
                    ->where('project_id', $project_id)
                    ->where('language_id', 'en-US')
                    ->first();
                
                if(!$en_translation)  
                {
                    $import_errors[] = "ERROR - import file, row($row): source(id=$source->id) has different key than import file. En translation doesn't exist."; 
                    continue; 
                }
                elseif($en_translation->$translation != $item[1])
                {
                    $import_errors[] = "ERROR - import file, row($row): source(id=$source->id) has different key than import file. En translation exists."; 
                    continue;
                }
            }
                
            if($translation)
            {
                if($translation->translation != $item[2])
                {
                     $update_count++;
                }
                
                $translation->translation = $item[2];
                $translation->is_published = 0;
                $translation->save();  
            }
            else
            {
                $translation = new Translation;
                $translation->source_id = $source->id;
                $translation->translation = $item[2];
                $translation->language_id = $language_id;
                $translation->project_id = $project_id;
                $translation->is_published = 0;
                $translation->save();
                $new_count++;
            }
        }
        if($import_errors)
        {
            Session::flash('errors', $import_errors);
        }
        
        Session::flash('message', "Imported $new_count new translations. Updated $update_count translations.");
        
        return redirect()->route('import');
    }
    
    public function publish()
    {
        $project = Project::find(session()->get('project_id'));
        $language = Language::find(session()->get('language_id'));
            
        $translations = Translation::where('project_id', $project->id)
                ->where('language_id', $language->id)
                ->get();
        
        $translations_array = array();
        
        foreach($translations as $translation)
        {
            $translations_array[$translation->source->key] = $translation->translation;
            $translation->is_published = 1;
            $translation->save();
        } 
        
        $json = json_encode($translations_array);
        
        $file_path = $project->path.'/resources/lang/'.$language->id.'.json';
        
        file_put_contents($file_path, $json);
        
        Session::flash('message', "$language->id language file has been published to $file_path");
        
        return redirect()->route('translations.index');
    }
}
