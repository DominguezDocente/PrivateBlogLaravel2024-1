<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\AuthorizedMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/', [HomeController::class, 'index'])
     ->name('home.index')
     ->middleware(AuthorizedMiddleware::class);

Route::get('/home/section/{id}', [HomeController::class, 'section'])
     ->name('home.section')
     ->middleware(AuthorizedMiddleware::class);

Route::get('/home/blog/{id}', [HomeController::class, 'blog'])
     ->name('home.blog')
     ->middleware(AuthorizedMiddleware::class);

include('web/sections.php');
include('web/blogs.php');
include('web/roles.php');
include('web/users.php');
