<?php

use App\Http\Controllers\AuthorsController;
use App\Http\Controllers\BlogPostsController;
use App\Http\Controllers\permissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UsersController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
// fastfood applicartions
Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('dashboard') // Redirect authenticated users to the dashboard
        : redirect()->route('login');    // Redirect unauthenticated users to the login page
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::resource('users', UsersController::class)->names('users');
    Route::resource('roles', RoleController::class)->names('roles');

    //authores
    Route::resource('authors', AuthorsController::class)->names('authors');
    //posts
    Route::resource('posts', BlogPostsController::class)->names('posts');
});
