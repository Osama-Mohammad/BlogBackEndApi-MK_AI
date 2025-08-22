<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');


Route::resource('category', CategoryController::class);
Route::resource('user', UserController::class);
Route::resource('post', PostController::class)->middleware('auth:sanctum');
Route::post('/post/{post}/comment', [CommentController::class, 'comment'])->middleware('auth:sanctum');
Route::delete('/post/comment/delete', [CommentController::class, 'destroy'])->middleware('auth:sanctum');
