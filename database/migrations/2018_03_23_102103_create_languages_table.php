<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        
        Schema::create('languages', function (Blueprint $table) {
            
            $table->string('id')->primary();
            $table->string('short');
            $table->string('name');
            $table->string('name_ascii');
            $table->tinyInteger('status');
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
        Schema::dropIfExists('languages');
    }
}
