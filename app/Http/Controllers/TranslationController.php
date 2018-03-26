<?php

namespace App\Http\Controllers;


use App\Translation;
use App\Source;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class TranslationController extends Controller
{
    public function export()
    {
        return view('export.index');   
    }
    
    public function exportAction( $project_id=1, $language='en-US')
    {
        $data = [];
        
        $sources = Source::where('project_id', $project_id)->get();
        
        foreach($sources as $key => $source)
        {
            $data[$key][] = $source->id; 
            $data[$key][] = $source->key;  
        }
        
        $path = storage_path('app/public/translations/');
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet()->fromArray(
            $data,  
            NULL       
        );
        
        $writer = new Xlsx($spreadsheet);
        
        $timestamp = Carbon::now();
        
        $writer->save($path.$language.'_'.$timestamp.'.xlsx');
        
        return redirect()->route('export');
    }
}
