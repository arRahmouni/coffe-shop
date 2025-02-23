<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\web\HomeController;
use App\Http\Controllers\Web\CategoryController;

Route::get('/', [HomeController::class, 'index'])->name('/');

Route::controller(CategoryController::class)->group(function () {
    Route::get('/{username}/categories'                     , 'index')->name('coffee.index'); 
    Route::get('/{username}/categories/{category}'          , 'show')->name('categories.products');
    Route::get('/{username}/{category}/product/{product}'   , 'showProduct')->name('products.show');
});

Route::view('/admin', 'admin');

Route::get('/admin/{pathMatch}', function () {
    return view('admin');
})->where('pathMatch', '.*');