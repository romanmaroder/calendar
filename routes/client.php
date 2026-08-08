<?php

use App\Http\Controllers\Client\ClientController;

Route::middleware(['auth'])->group(function () {

    Route::get('/clients/form-meta', [ClientController::class, 'formMeta']);

    Route::redirect('clients', '/clients/index');

    Route::get('/clients/form-meta', [ClientController::class, 'formMeta']);


    // Просмотр доступен тем, у кого clients.view
    Route::get('clients/archive', [ClientController::class, 'archive'])->name('clients.archive')->middleware('permission:clients.view');
    Route::get('/clients', [ClientController::class, 'index'])->name('clients')->middleware('permission:clients.view');
    Route::get('/clients/create', [ClientController::class, 'create'])->name('clients.create')->middleware('permission:clients.create');

    // Только те, у кого есть permission:clients.create, смогут делать POST
    Route::post('/clients/index', [ClientController::class, 'store'])->name('clients.store')->middleware('permission:clients.create');
    Route::get('/clients/{client}/edit', [ClientController::class, 'edit'])->name('clients.edit')->middleware('permission:clients.edit');

    // Только с правом edit могут делать PUT/PATCH
    Route::put('/clients/{client}', [ClientController::class, 'update'])->name('clients.update')->middleware('permission:clients.edit');
    Route::get('/clients/{client}', [ClientController::class, 'show'])->name('clients.show')->withTrashed();
    Route::put('/avatar/{client}', [ClientController::class, 'avatar'])->name('clients.avatar');

    // Только с правом delete могут удалять
    // Soft delete (один/несколько)
    Route::delete('/clients/bulk/soft', [ClientController::class, 'bulkSoftDelete'])->name('clients.bulk.soft')
        ->middleware('permission:clients.delete');
    Route::delete('/clients/{id}', [ClientController::class, 'softDelete'])->name('clients.soft.delete')->middleware('permission:clients.delete');
    // Force delete (один/несколько)
    Route::delete('/clients/bulk/force', [ClientController::class, 'bulkForceDelete'])->name('clients.bulk.force')
        ->middleware('permission:clients.delete');
    Route::delete('/clients/{id}/force', [ClientController::class, 'forceDelete'])->name('clients.force')->middleware
    ('permission:clients.delete');
    // Restore (один/несколько)
    Route::post('/clients/bulk/restore', [ClientController::class, 'bulkRestore'])->name('clients.bulk.restore');
    Route::post('/clients/{id}/restore', [ClientController::class, 'restore'])->name('clients.restore');

});


