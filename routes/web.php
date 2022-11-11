<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [FeedController::class, 'Index'])->name("feed.index");

Route::get('/profile/{id}', [ProfileController::class, 'Index'])->name("user.show");

Route::get('/people', [UserController::class, 'Index'])->name("user.index");

Route::get('/post/create', [PostController::class, 'create'])->name("post.create");

Route::get('/post/{id}', [PostController::class, 'show'])->name("post.show");

Route::post('/post/store', [PostController::class, 'store'])->name("post.store");
