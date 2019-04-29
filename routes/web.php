<?php

use App\Model\Resource;
use App\Model\Collection;
use Illuminate\Http\Request;
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

Route::resource('resources','ResourcesController');
Route::post('add-to-collection/{id}','ResourcesController@postAddCollectionToResource');
Route::post('remove-to-collection/{id}','ResourcesController@postRemoveCollectionToResource');
Route::post('add_favorites_resource/{id}','ResourcesController@postSetFavorite');


Route::resource('collections','CollectionsController');
Route::post('add-to-resources/{id}','CollectionsController@postAddResourceToCollection');
Route::post('remove-to-resources/{id}','CollectionsController@postRemoveResourceToCollection');
Route::post('add_favorites_collection/{id}','CollectionsController@postSetFavorite');


Route::get('/search','ResourcesController@search');
Route::post('collections/post', 'CollectionsController@store');
Route::post('resources/post', 'ResourcesController@store');


