<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use AWS\CRT\HTTP\Request;
use PHPUnit\TextUI\XmlConfiguration\Group;

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


require __DIR__ . '/auth.php';

//Public Routes

Route::get('/', [FeedController::class, 'Index'])->name("feed.index");

// User Routes

Route::middleware(['auth'])->group(function () {

    Route::get('/profile/{id}', [ProfileController::class, 'Index'])->name("user.show");

    Route::get('/post/create', [PostController::class, 'create'])->name("post.create");

    Route::get('/post/{id}', [PostController::class, 'show'])->name("post.show");

    Route::get('/post/{id}/edit', [PostController::class, 'edit'])->name("post.edit");

    Route::put('/post/{id}', [PostController::class, 'update'])->name("post.update");

    Route::delete('/post/{id}', [PostController::class, 'destroy'])->name("post.destroy");

    Route::post('post/like/{post_id}', [PostController::class, 'like'])->name("post.like");

    Route::post('/post/store', [PostController::class, 'store'])->name("post.store");

    Route::post('/follow/{user_id}', [UserController::class, 'follow'])->name("user.follow");

    Route::put('/user', [UserController::class, 'update'])->name('user.update');

    Route::get('/people', [UserController::class, 'Index'])->name("user.index");

    Route::get('/token/{name}', function ($name) {
        $user = auth()->user();
        $token = $user->createToken($name);
        return $token->plainTextToken;
    });
});


//Admin Routes
Route::middleware(['auth', 'isUserAdmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/posts', [AdminController::class, 'posts'])->name('posts');
    Route::delete('/user/{id}', [AdminController::class, 'deleteUser'])->name('user.destroy');
    Route::delete('/post/{id}', [AdminController::class, 'deletePost'])->name('post.destroy');
});
