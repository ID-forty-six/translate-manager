<?php

namespace App\Http\Controllers;

use App\Project;
use App\Language;
use App\Source;
use Illuminate\Http\Request;
use Session;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = Project::all();
        
        $path_errors = [];
             
        foreach($projects as $project)
        {
            if(!file_exists($project->path))
            {
                $path_errors[] = "WARNING! PROJECT:$project->name PATH:$project->path does not exist";
            }
        }
        
        return view('projects.index')->with(['projects'=>$projects, 'errors'=>$path_errors]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('projects.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $project = new Project;
        
        $project->name = $request->name;
        $project->framework = $request->framework;
        $project->path = $request->path;
        
        $project->save();
        
        return redirect('projects');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        $languages = Language::all();
        $sources = Source::where('project_id', $project->id)->get();
        
        $sources_count = count($sources);
        
        $data = [];
        
        foreach($languages as $language)
        {
            $translations = $project->translations->where('language_id', $language->id)->where('translation', '!=', null);
            if(count($sources) == 0)
            {
                $data['percent_complete'][$language->id] = 0;
            }
            else
            {
                $data['percent_complete'][$language->id] = round(count($translations)/count($sources)*100);
            }
        }
        
        return view('projects.show')->with(['project' => $project, 'languages'=>$languages, 'data'=>$data]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        return view('projects.edit')->with(['project' => $project]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        $project->name = $request->name;
        $project->framework = $request->framework;
        $project->path = $request->path;
        
        $project->save();
        
        return redirect('projects');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        $project->delete();
        
        return redirect('projects');
    }
}
