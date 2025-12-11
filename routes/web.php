<?php


use App\Http\Controllers\Upload\AvatarUploadController;
use App\Http\Controllers\Upload\AvatarController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::post('/upload',[AvatarUploadController::class,'store'] )->name('upload');
Route::post('/delete',[AvatarUploadController::class,'destroy'] )->name('delete');

Route::post('/api/upload', [AvatarController::class, 'store'])->name('store');
Route::delete('/api/destroy', [AvatarController::class, 'destroy'])->name('destroy');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
require __DIR__.'/user.php';
require __DIR__.'/client.php';
