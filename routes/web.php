<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/callback', function (Request $request) {
  $data = $request->all();
  $data['status'] = 'success';
  return response()->json($data);
});