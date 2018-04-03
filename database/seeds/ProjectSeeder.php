<?php

use Illuminate\Database\Seeder; 

class ProjectSeeder extends Seeder
{
    /**
     * Run the user seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('projects')->truncate();
		
		DB::table('projects')->insert([
			[
			'name'		=> 'Sendinn5.5',
			'framework' => 'laravel',
			'path' => '/var/www/localhost/sendinn5.5',
			],
		]);
        
        DB::table('projects')->insert([
			[
			'name'		=> 'test_project',
			'framework' => 'laravel',
			'path' => '/very/bad/path/test_project',
			],
		]);
    }
}