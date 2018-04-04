<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        
        Schema::create('translations', function (Blueprint $table) {
			
			$table->increments('id');
            
			$table->integer('source_id')->unsigned();
            $table->foreign('source_id')->references('id')->on('sources');
            
			$table->string('language_id');
            $table->foreign('language_id')->references('id')->on('languages');
            
            $table->string('translation')->nullable();
            
            $table->integer('project_id')->unsigned();
            $table->foreign('project_id')->references('id')->on('projects');
            
            $table->integer('is_published');
			$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('translations');
    }
}