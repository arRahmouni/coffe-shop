<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    dd('hello');
});

Route::get('/admin', function () {
    return view('admin');
});

Route::get('/admin/{pathMatch}', function () {
    return view('admin');
})->where('pathMatch', '.*');