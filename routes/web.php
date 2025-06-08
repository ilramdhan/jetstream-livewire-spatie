<?php

use App\Livewire\Post\Post;
use App\Livewire\RolePermission\RolePermissionManager;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/post', Post::class)->name('post');
    Route::get('/roles-permissions', RolePermissionManager::class)->name('roles.permissions.manager');
});
