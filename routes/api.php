<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('/articles/search', [App\Http\Controllers\API\ArticleController::class, 'search']);
Route::get('/articles/summary', [App\Http\Controllers\API\ArticleController::class, 'summary']);