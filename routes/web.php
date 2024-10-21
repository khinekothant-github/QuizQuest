<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });


Route::view('/403', 'errors.403')->name('403');


Route::get('/{any}',function(){
    return view('index');
})->where('any','.*');
