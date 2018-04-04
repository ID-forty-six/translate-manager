<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        
        Schema::create('sources', function (Blueprint $table) {
			
			$table->increments('id');
			$table->string('key');
            
            $table->integer('project_id')->unsigned();
            $table->foreign('project_id')->references('id')->on('projects');
            
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
        Schema::dropIfExists('sources');
    }
}

