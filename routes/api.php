<?php

use App\Http\Controllers\User\ArticleController;
use App\Http\Controllers\User\CommentController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ArticleController as AdminArticlesController;
use App\Http\Controllers\Admin\CommentController as AdminCommentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('jwt')->group(function () {
    Route::prefix('user')->group(function () {
        Route::prefix('articles')->controller(ArticleController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/create', 'store');
            Route::put('/edit/{article}', 'update');
            Route::delete('/delete/{article}', 'delete');
            Route::post('/like/{article}', 'like');
            Route::post('/rate/{article}', 'rate');
        });
        Route::prefix('comments')->controller(CommentController::class)->group(function () {
            Route::post('/', 'comment');
        });
    });

    Route::prefix('admin')->group(function () {
        Route::prefix('articles')->controller(AdminArticlesController::class)->group(function () {
            Route::get('/users/article', 'index');
            Route::put('/edit/{article}', 'update');
            Route::delete('/delete/{article}', 'delete');
        });
        Route::prefix('comments')->controller(AdminCommentController::class)->group(function () {
            Route::get('/{article}', 'showComments');
            Route::put('/visibility/{comment}','updateCommentVisibility');
        });

        Route::prefix('users')->controller(AdminUserController::class)->group(function () {
            Route::put('/{user}', 'update');
            Route::delete('/{user}', 'delete');
        });
    });
});

Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
});

Route::prefix('home')->controller(HomeController::class)->group(function () {
    Route::get('/index', 'index');
});
