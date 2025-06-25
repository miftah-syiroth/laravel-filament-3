<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Spatie\Analytics\Facades\Analytics;
use Spatie\Analytics\Period;
use App\Http\Controllers\AuthDebugController;

// Route::get('/callback', function (Request $request) {
//   $data = $request->all();
//   $data['status'] = 'success';
//   return response()->json($data);
// });

Route::get('/debug-auth', [AuthDebugController::class, 'debugAuth']);
Route::get('/test-oauth-callback', [AuthDebugController::class, 'testOAuthCallback']);

Route::get('/analytics-test', function () {
  $data = Analytics::fetchVisitorsAndPageViews(Period::days(7));
  dd($data);
});