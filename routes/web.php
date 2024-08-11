<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/user-lists', function () {
    return view('users'); // Make sure this points to your Blade view
});