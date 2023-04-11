<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Task\ProductController;
use App\Http\Controllers\Task\PlaceController;

Route::get('/', function () {
    return view('welcome');
});



Route::controller(PlaceController::class)->prefix('places')
    ->group(function (){
        Route::get('/','index')->name('places.index');
        Route::get('create','create')->name('places.create');
        Route::post('store','store')->name('places.store');
        Route::get('edit/{id}','edit')->name('places.edit');
        Route::post('update/{id}','update')->name('places.update');
        Route::get('delete/{id}','delete')->name('places.delete');

    });


Route::controller(ProductController::class)->prefix('products')
    ->group(function (){
        Route::get('/','index')->name('index');
        Route::get('create','create')->name('create');
        Route::post('store','store')->name('store');
        Route::get('edit/{id}','edit')->name('edit');
        Route::post('update/{id}','update')->name('update');

    });
