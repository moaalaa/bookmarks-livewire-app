<?php

use Illuminate\Support\Facades\Route;

Route::livewire('/', 'pages::bookmark-list')->name('home');
Route::livewire('/about', 'pages::about')->name('about');
