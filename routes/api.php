<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::get('/admin', function (Request $request) {
    return $request->admin();
})->middleware('auth:sanctum');
Route::get('/tupusat', function (Request $request) {
    return $request->tupusat();
})->middleware('auth:sanctum');
Route::get('/tuunit', function (Request $request) {
    return $request->tuunit();
})->middleware('auth:sanctum');
