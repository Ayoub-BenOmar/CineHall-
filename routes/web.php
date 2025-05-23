<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', function(){
    return view('register');
})->name('register');

Route::get('/login', function(){
    return view('login');
})->name('login');

Route::get('movie.create', function(){
    return view('movie.create');
})->name('movie.create');