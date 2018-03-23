<?php

use Illuminate\Database\Seeder; 

class UserSeeder extends Seeder
{
    /**
     * Run the user seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();
		
		DB::table('users')->insert([
			[
			'name'		=> 'Mindaugas Jaceris',
			'email'		=> 'mjaceris@yahoo.com',
			'password'	=> bcrypt('kdc8420x'),
			],
			[
			'name'		=> 'Mindaugas Jaceris',
			'email'		=> 'mindaugas.jaceris@gmail.com',
			'password'	=> bcrypt('kdc8420x'),
			],
		]);
    }
}