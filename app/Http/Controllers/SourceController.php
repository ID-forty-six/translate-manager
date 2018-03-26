<?php

namespace App\Http\Controllers;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Events\Dispatcher;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Finder\Finder;
use App\Source;

class SourceController extends Controller
{
    
    public function index()
    {
        $sources = Source::all();
        
        return view('sources.index')->with(['sources'=>$sources]);
    }
    
    public function findSources($path = '/var/www/localhost/sendinn5.5')
    {
        DB::table('sources')->truncate();
        
        $groupKeys = array();
        $stringKeys = array();
        $functions =  array('trans', 'trans_choice', 'Lang::get', 'Lang::choice', 'Lang::trans', 'Lang::transChoice', '@lang', '@choice', '__');

        $groupPattern =                              // See http://regexr.com/392hu
            "[^\w|>]".                          // Must not have an alphanum or _ or > before real method
            "(".implode('|', $functions) .")".  // Must start with one of the functions
            "\(".                               // Match opening parenthesis
            "[\'\"]".                           // Match " or '
            "(".                                // Start a new group to match:
                "[a-zA-Z0-9_-]+".               // Must start with group
                "([.|\/][^\1)]+)+".             // Be followed by one or more items/keys
            ")".                                // Close group
            "[\'\"]".                           // Closing quote
            "[\),]";                            // Close parentheses or new parameter

        $stringPattern =
            "[^\w|>]".                                     // Must not have an alphanum or _ or > before real method
            "(".implode('|', $functions) .")".             // Must start with one of the functions
            "\(".                                          // Match opening parenthesis
            "(?P<quote>['\"])".                            // Match " or ' and store in {quote}
            "(?P<string>(?:\\\k{quote}|(?!\k{quote}).)*)". // Match any string that can be {quote} escaped
            "\k{quote}".                                   // Match " or ' previously matched
            "[\),]";                                       // Close parentheses or new parameter

        // Find all PHP + Twig files in the app folder, except for storage
        $finder = new Finder();
        $finder->in($path)->exclude('storage')->name('*.php')->name('*.twig')->name('*.vue')->files();
        

        /** @var \Symfony\Component\Finder\SplFileInfo $file */
        foreach ($finder as $file) {
            // Search the current file for the pattern
            if(preg_match_all("/$groupPattern/siU", $file->getContents(), $matches)) {
                // Get all matches
                foreach ($matches[2] as $key) {
                    $groupKeys[] = $key;
                }
            }

            if(preg_match_all("/$stringPattern/siU", $file->getContents(), $matches)) {
                foreach ($matches['string'] as $key) {
                    if (preg_match("/(^[a-zA-Z0-9_-]+([.][^\1)\ ]+)+$)/siU", $key, $groupMatches)) {
                        // group{.group}.key format, already in $groupKeys but also matched here
                        // do nothing, it has to be treated as a group
                        continue;
                    }
                    $stringKeys[] = $key;
                }
            }
        }
        
        // Remove duplicates
        $groupKeys = array_unique($groupKeys);
        $stringKeys = array_unique($stringKeys);
        
        foreach($groupKeys as $groupkey)
        {
            // kad isvengt pvz (excl. VAT) suamisymo su grupe
            if(strpos($groupkey, " ") !== false)
            {
                break;
            }
            
            list($group, $item) = explode('.', $groupkey, 2);
            
            $source = Source::where('key', $item)->where('group', $group)->first();
            
            if (!$source)
            {
                $source = new Source;
                $source->key = $item;
                $source->group = $group;
                $source->project_id = 1;
                $source->save();
            }
        }
        
        foreach($stringKeys as $key)
        {
            $source = Source::where('key', $key)->first();
            
            if (!$source)
            {
                $source = new Source;
                $source->key = $key;
                $source->project_id = 1;
                $source->save();
            }
        }
        
        return redirect('sources');
    }
}
