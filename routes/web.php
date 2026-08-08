<?php


use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Upload\AvatarController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/*Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::resource('roles', RoleController::class);
});
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::resource('permissions', PermissionController::class)->only(['index', 'store', 'destroy']);
});*/

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('roles', RoleController::class);
        Route::resource('permissions', PermissionController::class);
    });



Route::post('/api/upload', [AvatarController::class, 'store'])->name('store');
Route::delete('/api/destroy', [AvatarController::class, 'destroy'])->name('destroy');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
require __DIR__.'/company.php';
require __DIR__.'/user.php';
require __DIR__.'/branch.php';
require __DIR__.'/client.php';
