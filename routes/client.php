<?php

use App\Http\Controllers\Client\ClientController;

Route::middleware(['auth'])->group(function () {
    Route::redirect('clients', '/clients/index');

    Route::get('clients/archive', [ClientController::class, 'archive'])->name('clients.archive');
    Route::get('/clients', [ClientController::class, 'index'])->name('clients');
    Route::get('/clients/create', [ClientController::class, 'create'])->name('clients.create');
    Route::post('/clients/index', [ClientController::class, 'store'])->name('clients.store');
    Route::get('/clients/{client}/edit', [ClientController::class, 'edit'])->name('clients.edit');
    Route::put('/clients/{client}', [ClientController::class, 'update'])->name('clients.update');
    Route::get('/clients/{client}', [ClientController::class, 'show'])->name('clients.show')->withTrashed();
    Route::put('/avatar/{client}', [ClientController::class, 'avatar'])->name('clients.avatar');
    // Soft delete (один/несколько)
    Route::delete('/clients/bulk/soft', [ClientController::class, 'bulkSoftDelete'])->name('clients.bulk.soft');
    Route::delete('/clients/{id}', [ClientController::class, 'softDelete'])->name('clients.soft.delete');
    // Force delete (один/несколько)
    Route::delete('/clients/bulk/force', [ClientController::class, 'bulkForceDelete'])->name('clients.bulk.force');
    Route::delete('/clients/{id}/force', [ClientController::class, 'forceDelete'])->name('clients.force');
    // Restore (один/несколько)
    Route::post('/clients/bulk/restore', [ClientController::class, 'bulkRestore'])->name('clients.bulk.restore');
    Route::post('/clients/{id}/restore', [ClientController::class, 'restore'])->name('clients.restore');

});


