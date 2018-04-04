<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Project;
use App\Language;
use App\Translation;

class Publish extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:translations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish translations in projects';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $projects = Project::all();
        $languages = Language::all();
        
        foreach($projects as $project)
        {
            foreach($languages as $language)
            {
                $translations = Translation::where('language_id', $language->id)->where('project_id', $project->id)->get();
                
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
            }   
        }
        
        echo "Translations published";
    }
}
