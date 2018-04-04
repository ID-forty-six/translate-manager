<?php

namespace App\Http\Controllers;

use App\Language;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    
    public function index()
    {
        $languages = Language::all();
        
        return view('languages.index')->with(['languages'=>$languages]);
    }
    
    public function create()
    {
        return view('languages.create');
    }

    public function store(Request $request)
    {
        $language = new Language;
        
        $language->id = $request->id;
        $language->short = $request->short;
        $language->name = $request->name;
        $language->name_ascii = $request->name_ascii;
        
        $language->save();
        
        return redirect('languages');
    }

    public function show(Language $language)
    {
        //
    }

    public function edit(Language $language)
    {
        return view('languages.edit')->with(['language' => $language]);
    }

    public function update(Request $request, Language $language)
    {
        $language->short = $request->short;
        $language->name = $request->name;
        $language->name_ascii = $request->name_ascii;
        $language->status = $request->status;
        
        $language->save();
        
        return redirect('languages');
    }

    public function destroy(Language $language)
    {
        $language->delete();
        
        return redirect('languages');
    }
}
