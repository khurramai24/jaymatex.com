<?php

use Illuminate\Support\Facades\Route;

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

Route::get("sitemap.xml", array(
    "as"   => "sitemap",
    "uses" => "webController@sitemap",
));

Route::get('manager/login', 'webController@getManagerLogin');
Route::post('manager/login', 'webController@postManagerLogin');
Route::prefix('manager')->middleware('manager')->group(function () {
    //PRODUITS
    Route::get('/', 'webController@getProduits');
    Route::get('produit/{id}', 'webController@getProduit');
    Route::post('saveProduit', 'webController@saveProduit');
    Route::get('addProduit', 'webController@getAddProduit');
    Route::post('addProduit', 'webController@postAddProduit');

    //CONTACTS
    Route::get('contacts', 'webController@getContacts');
    Route::get('delcontact/{id}', 'webController@delContacts');
    //LOGOUT
    Route::get('logout', 'webController@logOutManager');
});

Route::get('/', 'webController@redirectIndex');
Route::get('/{lang}', 'webController@index');
Route::get('/p/{lang}/{slug}', 'webController@page');
Route::get('/as/{lang}', 'webController@articles');
Route::get('/{lang}/{slug}', 'webController@article');

Route::post('postForm', 'webController@postForm');



