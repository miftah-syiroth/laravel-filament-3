<?php

use Illuminate\Support\Facades\Route;

Route::get('/articles/search', [App\Http\Controllers\API\ArticleController::class, 'search']);
Route::get('/articles/summary', [App\Http\Controllers\API\ArticleController::class, 'summary']);