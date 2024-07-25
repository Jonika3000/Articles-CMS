<?php

use App\Http\Controllers\ArticleController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('/article')->group(function () {
    Route::get('/', [ArticleController::class, 'index'])->name('article_all');
    Route::post('/', [ArticleController::class, 'store'])->name('article_store');
    Route::get('/show/{id}', [ArticleController::class, 'show'])->name('article_details');

    Route::middleware(['auth'])->group(function () {
        Route::put('/{id}', [ArticleController::class, 'update'])
            ->name('articles_update')
            ->middleware(['check.article.author']);

        Route::delete('/{id}', [ArticleController::class, 'destroy'])
            ->name('article_destroy')
            ->middleware(['check.article.author']);
    });
});
