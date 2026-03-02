<?php

use Illuminate\Support\Facades\Route;


Route::livewire('/', 'pages::index');

Route::middleware('guest')->group(function () {
  Route::livewire('/login', 'pages::auth.login')->name('login');
  Route::livewire('/daftar', 'pages::auth.daftar');
});

Route::middleware(['auth', 'admin'])->group(function () {
  Route::livewire('/dashboard/admin/fakultas', 'pages::admin.fakultas.index')->name('admin.fakultas.index');

  Route::livewire('/dashboard/admin/fakultas/create', 'pages::admin.fakultas.create')->name('admin.fakultas.create');

  Route::livewire('/dashboard/admin/fakultas/{faculty}/edit', 'pages::admin.fakultas.edit')->name('admin.fakultas.edit');

  Route::livewire('/dashboard/admin/prodi', 'pages::admin.prodi.index')->name('admin.prodi.index');

  Route::livewire('/dashboard/admin/prodi/create', 'pages::admin.prodi.create')->name('admin.prodi.create');

  Route::livewire('/dashboard/admin/prodi/{department}/edit', 'pages::admin.prodi.edit')->name('admin.prodi.edit');
});