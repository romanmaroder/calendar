<?php

use App\Http\Controllers\Client\ClientController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    // --- Кастомные роуты (всегда ПЕРЕД resource) ---

    Route::get('/clients/form-meta', [ClientController::class, 'formMeta'])
        ->name('clients.form-meta')
        ->middleware('permission:clients.view');

    Route::get('/clients/archive', [ClientController::class, 'archive'])
        ->name('clients.archive')
        ->middleware('permission:users.delete');

    // Аватар
    Route::put('/avatar/{client}', [ClientController::class, 'avatar'])
        ->name('avatar')
        ->middleware('permission:clients.edit');

    // Soft delete (одна запись)
    Route::delete('/clients/{id}', [ClientController::class, 'softDelete'])
        ->name('clients.soft.delete')
        ->middleware('permission:clients.delete');

    // Bulk soft delete
    Route::delete('/clients/bulk/soft', [ClientController::class, 'bulkSoftDelete'])
        ->name('clients.bulk.soft')
        ->middleware('permission:clients.delete');

    // Force delete (одна запись)
    Route::delete('/clients/{id}/force', [ClientController::class, 'forceDelete'])
        ->name('clients.force')
        ->middleware('permission:clients.force-delete');


    // Bulk force delete
    Route::delete('/clients/{ids}/bulk/force', [ClientController::class, 'bulkForceDelete'])
        ->name('clients.bulk.force')
        ->middleware('permission:clients.force-delete');

    // Restore (одна запись)
    Route::post('/clients/{id}/restore', [ClientController::class, 'restore'])
        ->name('clients.restore')
        ->middleware('permission:clients.restore');

    // Bulk restore
    Route::post('/clients/{ids}/bulk/restore', [ClientController::class, 'bulkRestore'])
        ->name('clients.bulk.restore')
        ->middleware('permission:clients.restore');

    // 4. Resource — в самом конце, чтобы не «перехватывать» кастомные пути
    Route::resource('clients', ClientController::class)
        ->only(['index', 'create', 'store', 'edit', 'update', 'show'])
        ->middlewareFor('index', ['permission:clients.view'])
        ->middlewareFor('show', ['permission:clients.view'])
        ->middlewareFor(['create', 'store'], ['permission:clients.create'])
        ->middlewareFor(['edit', 'update'], ['permission:clients.edit'])
        ->withTrashed(['show']);
});
