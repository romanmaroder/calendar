<?php

use App\Http\Controllers\User\UserController;

Route::middleware(['auth'])->group(function () {
    Route::redirect('users', '/users/index');

    Route::get('users/archive', [UserController::class, 'archive'])->name('users.archive');
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show')->withTrashed();
    Route::put('/avatar/{user}', [UserController::class, 'avatar'])->name('users.avatar');
    // Soft delete (один/несколько)
    Route::delete('/users/bulk/soft', [UserController::class, 'bulkSoftDelete'])->name('users.bulk.soft');
    Route::delete('/users/{id}', [UserController::class, 'softDelete'])->name('users.soft.delete');
    // Force delete (один/несколько)
    Route::delete('/users/bulk/force', [UserController::class, 'bulkForceDelete'])->name('users.bulk.force');
    Route::delete('/users/{id}/force', [UserController::class, 'forceDelete'])->name('users.force');
    // Restore (один/несколько)
    Route::post('/users/bulk/restore', [UserController::class, 'bulkRestore'])->name('users.bulk.restore');
    Route::post('/users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
});

