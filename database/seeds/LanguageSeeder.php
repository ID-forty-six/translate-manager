<?php

use Illuminate\Database\Seeder; 

class LanguageSeeder extends Seeder
{
    /**
     * Run the user seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('languages')->truncate();
		
		DB::table('languages')->insert([
			[
            'id'		=> 'lt-LT',
            'short'		=> 'lt',
            'name'		=> 'Lietuvių',
            'name_ascii'=> 'Lithuanian',
            'status'	=> 1,  
			],
            [
            'id'		=> 'en-US',
            'short'		=> 'en',
            'name'		=> 'English',
            'name_ascii'=> 'English',
            'status'	=> 1,
			],
            [
            'id'		=> 'pl-PL',
            'short'		=> 'pl',
            'name'		=> 'Polish',
            'name_ascii'=> 'Polish',
            'status'	=> 1,
			],
		]);
    }
}