<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Storage;
use Session;
use Artisan;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as WriterXlsx;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as ReaderXlsx;

use App\Translation;
use App\Source;
use App\Project;
use App\Language;


class TranslationController extends Controller
{

    public function index(Request $request)
    {
        $projects = Project::all()->load('sources');
        
        // sessions vars
        if (isset($request->from_lang_id))
        { 
            session([ 'from_lang_id' => $request->from_lang_id ]);
        }
        elseif(!session()->has('from_lang_id'))
        {
            session([ 'from_lang_id' => 'en-US' ]);
        }
        
        if (isset($request->to_lang_id))
        { 
            session([ 'to_lang_id' => $request->to_lang_id ]);
        }
        elseif(!session()->has('to_lang_id'))
        {
            session([ 'to_lang_id' => 'en-US' ]);
        }
        
        if (isset($request->project_id))
        { 
            session([ 'project_id' => $request->project_id ]);
        }
        elseif(!session()->has('project_id') && !$projects->isEmpty())
        {
            session([ 'project_id' => $projects->first()->id ]);
        }
        
        // data
        $projects = Project::all()->load('sources');
        $languages = Language::all();
        $translations = Translation::all()->load('source');
        
        $sources = Source::where('project_id', session()->get('project_id'))->get();
        
        $keys = array();
        
        foreach($sources as $source)
        {
            $keys[$source->id]['id'] = $source->id;
           
            if(session()->get('from_lang_id') == 'en-US')
            {
                 $keys[$source->id]['key'] = $source->key;
            }
            else
            {
                $key = $source->translations->where('language_id', session()->get('from_lang_id'))->first();
                
                if($key && $key->translation)
                {
                    $keys[$source->id]['key'] = $key->translation;
                }
                else
                {
                    $keys[$source->id]['key'] = $source->key;
                }
            }
            
            $translation = $source->translations->where('language_id', session()->get('to_lang_id'))->first();
                
            if($translation)
            {
                $keys[$source->id]['translation'] = $translation;
            }
            else
            {
                $keys[$source->id]['translation'] = null;
            }
        }
        
        return view('translations.index')->with(['projects'=>$projects, 'languages'=>$languages, 'translations'=>$translations, 'sources'=>$sources, 'keys'=>$keys]);   
    }
    
    
    public function export()
    {
        $projects = Project::all();
        $languages = Language::all();
        
        return view('export.index')->with(['projects'=>$projects, 'languages'=>$languages]);   
    }
    
    public function exportAction( Request $request )
    {
        // sessions vars
        if (isset($request->language_id))
        { 
            session([ 'language_id' => $request->language_id ]);
        }
       
        $language_id = $request->language_id;
        
        // init empty data array
        $data = [];
        
        // only get translations with current language
        $sources = Source::all()->load([
            'translations'=>function ($query) {
                $query->where('language_id', session()->get('language_id'));
            }
        ]);
        
        // generate data array
        foreach($sources as $key => $source)
        {
            // 1 column: source ID
            $data[$key][] = $source->id;
            
            // if language is en-US then take source->key
            if($language_id == "en-US")
            {
                $data[$key][] = $source->key;
            }
            else
            {
                // check if source has en_us translation and use it instead of source
                $en_translation = Translation::where('source_id', $source->id)->where('language_id', 'en-US')->where('translation', '!=', null)->first();
                
                // 2 column: en-US translation or source-key
                if($en_translation)
                {
                    $data[$key][] = $en_translation->translation;
                }
                else
                {
                    $data[$key][] = $source->key;
                }
            }
            
            //3 column: translation
            foreach($source->translations as $t_key => $translation)
            {
                $data[$key][] = $translation->translation;
            }
        }
        
        // create new spreadsheet
        $spreadsheet = new Spreadsheet();
        
        // select sheet
        $sheet = $spreadsheet->getActiveSheet();
        
        //Create header row
        $headers = ['id', 'key', session()->get('language_id')];
        array_unshift($data, $headers);
        
        // populate excel file
        $sheet->fromArray(
            $data,  
            NULL       
        );
        
        // create excel writer
        $writer = new WriterXlsx($spreadsheet);
        
        // create file name
        $timestamp = strtotime(Carbon::now());
        $fileName = $language_id.'_'.$timestamp.'.xlsx';
        
        // path where export files will be saved
        $path = config('app.export_path');
        
        // save file to path
        $writer->save($path.$fileName);
        
        Session::flash('message', "File $fileName exported to $path");
        
        return redirect()->route('export');
    }
    
     /*
      * Used for creating or updating translations in UI
      */
    public function findOrCreate(Request $request)
    {

        $source = Source::find($request->source_id);
           
        if (isset($request->translation_id)) 
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
            $translation->language_id = session()->get('to_lang_id');
            $translation->project_id = $source->project_id;
            $translation->is_published = 0;
            $translation->save(); 
        }
        
        return redirect()->back();   
    }
    
    public function import()
    {
        $languages = Language::all();
        
        return view('import.index')->with(['languages'=>$languages]);   
    }
    
    public function importAction( Request $request )
    {

        //preg_match_all("/\:\w+/ium", $test, $matches);
        
        // language session var
        if (isset($request->language_id))
        { 
            session([ 'language_id' => $request->language_id ]);
        }
        
        $language_id = session()->get('language_id');
        
        // create xlsx reader
        $reader = new ReaderXlsx;
        $reader->setReadDataOnly(true);
        
        // load spreadsheet
        $spreadsheet = $reader->load($request->file('upload'));
        
        // populate array from loaded excel file
        $translations_array = $spreadsheet->getActiveSheet()->toArray();
        
        // delete header row
        array_shift( $translations_array );
    
        $sources = array();
        
        // set up counts
        $new_count = 0;
        $update_count = 0;
        
        // set up empty errors aray
        $import_errors = [];
        
        //
        foreach ($translations_array as $key=>$item) 
        {
            // error row will be +2 cuz of start from 0 and header row
            $row = $key + 2;
            
            preg_match_all("/\:\w+/ium", $item[2], $trans_matches);
            
            if($item[0] == null || $item[1] == null)
            {
                $import_errors[] = "ERROR - import file, row($row): missing source id or key";
                continue;
            }
            
            // randam source pagal pirmo stulpelio nurodyta id
            $source = Source::find($item[0]);
            
            // check if source exists
            if(!$source)
            {
                $import_errors[] = "ERROR - import file, row($row): source(id=$item[0]) does not exist!";
                continue;
            }
            
            // tikrinam ar sutampa ID ir keys
            if( $item[1] != $source->key)
            {
                // jei nesutampa, tikrinam ar sutam su en-US translationu
                $en_translation = Translation::where('source_id', $source->id)
                    ->where('language_id', 'en-US')
                    ->first();
                
                if(!$en_translation)  
                {
                    $import_errors[] = "ERROR - import file, row($row): source(id=$source->id) has different key than import file. En translation doesn't exist."; 
                    continue; 
                }
                
                // tikrinam ar sutampa :var masyvai en-US translatione ir faile
                preg_match_all("/\:\w+/ium", $en_translation->translation, $src_matches);
              
                if($src_matches != $trans_matches && $item[2] != null)
                {
                    $import_errors[] = "ERROR - import file, row($row): translated variables!"; 
                    continue;  
                }
                
                if($en_translation->$translation != $item[1])
                {
                    $import_errors[] = "ERROR - import file, row($row): source(id=$source->id) has different key than import file. En translation exists."; 
                    continue;
                }       
                
            }
            else
            {
                // tikrinam ar sutampa :var masyvai source->key ir faile
                preg_match_all("/\:\w+/ium", $source->key, $src_matches);
              
                if($src_matches != $trans_matches && $item[2] != null)
                {
                    $import_errors[] = "ERROR - import file, row($row): translated variables!"; 
                    continue;  
                }
                
            }
            
            $translation = Translation::where('source_id', $source->id)
                ->where('language_id', $language_id)
                ->first();
            
            // if source translation already exists, update otherwise, create new translation
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
                $translation->project_id = $source->project_id;
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
    
    /*
     * Publish all translations to projects
     */
    public function publish()
    {
        
        Artisan::call('publish:translations');
        
        Session::flash('message', "All translations has been published");
        
        return redirect()->route('translations.index');
    }
}
