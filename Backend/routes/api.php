<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group([

    'middleware' => 'api',
    'prefix' => 'auth',

], function ($router) {
    Route::post('login', [UserController::class, 'login']);
    Route::post('register', [UserController::class, 'register']);
    Route::post('logout', [UserController::class, 'logout']);
});
Route::group([
    'middleware' => 'api',
], function ($router) {
    Route::get('user_posts', [PostController::class, 'userPosts']);
    Route::post('post/show', [PostController::class, 'show']);
    Route::post('post/create', [PostController::class, 'store']);
    Route::post('post/update', [PostController::class, 'update']);
    Route::post('post/delete', [PostController::class, 'destroy']);
    Route::post('post/add_comment', [CommentController::class, 'store']);
    Route::post('post/delete_comment', [CommentController::class, 'destroy']);


    Route::get('/notification', [NotificationController::class, 'index']);
});
Route::get('posts', [PostController::class, 'allPosts']);
