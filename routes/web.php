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
    ->prefix('admin')->name('admin.')
    ->group(function () {
        Route::resource('roles', RoleController::class);
        Route::resource('permissions', PermissionController::class);
    });

/*Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'permission:permissions.view'])
    ->group(function () {
    // CRUD для разрешений
    Route::resource('permissions', PermissionController::class);
});*/


/*Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Roles
        Route::get('roles', [RoleController::class, 'index'])->middleware('permission:roles.view')->name('roles.index');
        Route::post('roles', [RoleController::class, 'store'])->middleware('permission:roles.create')->name('roles.store');
        Route::get('roles/create', [RoleController::class, 'create'])->middleware('permission:roles.create')->name('roles.create');
        Route::get('roles/{role}', [RoleController::class, 'show'])->middleware('permission:roles.view')->name('roles.show');
        Route::get('roles/{role}/edit', [RoleController::class, 'edit'])->middleware('permission:roles.edit')->name('roles.edit');
        Route::put('roles/{role}', [RoleController::class, 'update'])->middleware('permission:roles.edit')->name('roles.update');
        Route::delete('roles/{role}', [RoleController::class, 'destroy'])->middleware('permission:roles.delete')->name('roles.destroy');

        // Permissions
        Route::get('permissions', [PermissionController::class, 'index'])->middleware('permission:permissions.view')->name('permissions.index');
        Route::post('permissions', [PermissionController::class, 'store'])->middleware('permission:permissions.create')->name('permissions.store');
        Route::get('permissions/create', [PermissionController::class, 'create'])->middleware('permission:permissions.create')->name('permissions.create');
        Route::get('permissions/{role}', [PermissionController::class, 'show'])->middleware('permission:permissions.view')->name('permissions.show');
        Route::get('permissions/{role}/edit', [PermissionController::class, 'edit'])->middleware('permission:permissions.edit')->name('permissions.edit');
        Route::delete('permissions/{permission}', [PermissionController::class, 'destroy'])->middleware('permission:permissions.delete')->name('permissions.destroy');
        // edit/update можно добавить, если нужны, с permission:permissions.edit
    });*/


Route::post('/api/upload', [AvatarController::class, 'store'])->name('store');
Route::delete('/api/destroy', [AvatarController::class, 'destroy'])->name('destroy');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
require __DIR__.'/company.php';
require __DIR__.'/user.php';
require __DIR__.'/branch.php';
require __DIR__.'/client.php';
