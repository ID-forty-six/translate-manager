<?php

namespace App\Http\Controllers;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Events\Dispatcher;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Finder\Finder;
use App\Source;
use App\Project;
use Illuminate\Http\Request;
use Session;

class SourceController extends Controller
{
    
    public function index()
    {
        $projects = Project::all();
        
        if (isset($request->project_id))
        { 
            session([ 'project_id' => $request->project_id ]);
        }
        elseif(!session()->has('project_id') && !$projects->isEmpty())
        {
            session([ 'project_id' => $projects->first()->id ]);
        }
        
        $sources = Source::all()->load([
            'translations'=>function ($query) {
                $query->where('project_id', session()->get('project_id'));
            }
        ]);
        
        return view('sources.index')->with(['sources'=>$sources, 'projects'=>$projects ]);
    }
    
    public function findSources()
    {
        
        $projects = Project::all();
        
        $stringKeys = array();
        $functions =  array('trans', 'trans_choice', 'Lang::get', 'Lang::choice', 'Lang::trans', 'Lang::transChoice', '@lang', '@choice', '__');

        $stringPattern =
            "[^\w|>]".                                     // Must not have an alphanum or _ or > before real method
            "(".implode('|', $functions) .")".             // Must start with one of the functions
            "\(".                                          // Match opening parenthesis
            "(?P<quote>['\"])".                            // Match " or ' and store in {quote}
            "(?P<string>(?:\\\k{quote}|(?!\k{quote}).)*)". // Match any string that can be {quote} escaped
            "\k{quote}".                                   // Match " or ' previously matched
            "[\),]";                                       // Close parentheses or new parameter
        
        $count = 0;
        
        foreach($projects as $project)
        {
            $finder = new Finder();
            
            $finder->in($project->path)->exclude('storage')->name('*.php')->name('*.twig')->name('*.vue')->files();
            
            /** @var \Symfony\Component\Finder\SplFileInfo $file */
            foreach ($finder as $file) 
            {
            // Search the current file for the pattern
                
                if(preg_match_all("/$stringPattern/siU", $file->getContents(), $matches)) 
                {
                    foreach ($matches['string'] as $key) {
                        if (preg_match("/(^[a-zA-Z0-9_-]+([.][^\1)\ ]+)+$)/siU", $key, $groupMatches)) 
                        {
                        // group{.group}.key format, already in $groupKeys but also matched here
                        // do nothing, it has to be treated as a group
                        continue;
                        }
                        $stringKeys[] = $key;
                    }
                }
            }
            
            // Remove duplicates
            $stringKeys = array_unique($stringKeys);
        
            foreach($stringKeys as $key)
            {
                $source = Source::where('project_id', $project->id)->where('key', $key)->first();
                if (!$source)
                {
                    $source = new Source;
                    $source->key = $key;
                    $source->project_id = $project->id;
                    $source->save();
                    $count++;
                }
            }
        }
        
        Session::flash('message', "Scan completed. $count new sources added");
        
        return redirect()->route('sources');
    }
}
