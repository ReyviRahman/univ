<?php

use Illuminate\Support\Facades\Route;


Route::livewire('/', 'pages::index');

Route::livewire('/login', 'pages::auth.login');

Route::livewire('/daftar', 'pages::auth.daftar');

