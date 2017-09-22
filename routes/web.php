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

Auth::routes();
//Social Auth routes
Route::get('login/{service}', 'Auth\SocialLoginController@redirect');
Route::get('login/{service}/callback', 'Auth\SocialLoginController@callback');

//Guarded routes
Route::middleware(['auth'])->group(function () {

    Route::get('/', 'ContactsController@index')
        ->name('contacts.index') ;

    Route::get('/contacts/search', 'ContactsController@search')->name('contacts.search');
    Route::resource('/contacts', 'ContactsController');

});


