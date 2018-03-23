<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        
        Schema::create('projects', function (Blueprint $table) {
			
			$table->increments('id');
            $table->string('name');
			$table->string('framework');
			$table->string('path');
			$table->timestamps();
            
        });
        
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
		
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
