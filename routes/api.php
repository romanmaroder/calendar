<?php

use App\Http\Controllers\Upload\UserAvatarUploadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('/upload',[UserAvatarUploadController::class,'store'] );
Route::post('/delete',[UserAvatarUploadController::class,'delete'] );