<?php

use App\Http\Controllers\Upload\AvatarUploadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/upload',[AvatarUploadController::class,'store'] );
Route::post('/delete',[AvatarUploadController::class,'delete'] );