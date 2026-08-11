<?php

use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    // --- Кастомные роуты (всегда ПЕРЕД resource) ---

    Route::get('/users/form-meta', [UserController::class, 'formMeta'])
        ->name('users.form-meta')
        ->middleware('permission:users.view');

    Route::get('/users/archive', [UserController::class, 'archive'])
        ->name('users.archive')
        ->middleware('permission:users.delete');

    // Аватар
    Route::put('/avatar/{user}', [UserController::class, 'avatar'])
        ->name('avatar')
        ->middleware('permission:users.edit');


    // Soft delete (одна запись)
    Route::delete('/users/{id}', [UserController::class, 'softDelete'])
        ->name('users.soft.delete')
        ->middleware('permission:users.delete');

    // Bulk soft delete
    Route::delete('/users/bulk/soft', [UserController::class, 'bulkSoftDelete'])
        ->name('users.bulk.soft')
        ->middleware('permission:users.delete');

    // Force delete (одна запись)
    Route::delete('/users/{id}/force', [UserController::class, 'forceDelete'])
        ->name('users.force')
        ->middleware('permission:users.force-delete');


    // Bulk force delete
    Route::delete('/users/{ids}/bulk/force', [UserController::class, 'bulkForceDelete'])
        ->name('users.bulk.force')
        ->middleware('permission:users.force-delete');

    // Restore (одна запись)
    Route::post('/users/{id}/restore', [UserController::class, 'restore'])
        ->name('users.restore')
        ->middleware('permission:users.restore');

    // Bulk restore
    Route::post('/users/{ids}/bulk/restore', [UserController::class, 'bulkRestore'])
        ->name('users.bulk.restore')
        ->middleware('permission:users.restore');


    // 4. Resource — в самом конце, чтобы не «перехватывать» кастомные пути
    Route::resource('users', UserController::class)
        ->only(['index', 'create', 'store', 'edit', 'update', 'show'])
        ->middlewareFor('index', ['permission:users.view'])
        ->middlewareFor('show', ['permission:users.view'])
        ->middlewareFor(['create', 'store'], ['permission:users.create'])
        ->middlewareFor(['edit', 'update'], ['permission:users.edit'])
        ->withTrashed(['show']);
});
