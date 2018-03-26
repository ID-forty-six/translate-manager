<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/sources/find', 'SourceController@findSources')->name('findSources');
Route::get('/sources', 'SourceController@index')->name('sources');

Route::resource('projects', 'ProjectController');
Route::resource('languages', 'LanguageController');

Route::get('/translations/export', 'TranslationController@export')->name('export');
Route::get('/translations/exportaction', 'TranslationController@exportAction')->name('exportAction');


