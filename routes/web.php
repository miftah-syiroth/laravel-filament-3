<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Spatie\Analytics\Facades\Analytics;
use Spatie\Analytics\Period;

// Route::get('/callback', function (Request $request) {
//   $data = $request->all();
//   $data['status'] = 'success';
//   return response()->json($data);
// });


Route::get('/analytics-test', function () {
  $data = Analytics::fetchVisitorsAndPageViews(Period::days(7));
  dd($data);
});