<?php

use App\Http\Controllers\Client\ClientController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    // 1. Сначала — все кастомные роуты, которые могут конфликтовать по URI (особенно с {client})

    // Bulk-операции (отдельный префикс, чтобы не пересекаться с resource)
    Route::prefix('clients/bulk')->name('clients.bulk.')->group(function () {
        Route::delete('soft', [ClientController::class, 'bulkSoftDelete'])
            ->name('soft')
            ->middleware('permission:clients.delete');

        Route::delete('force', [ClientController::class, 'bulkForceDelete'])
            ->name('force')
            ->middleware('permission:clients.force-delete'); // отдельное разрешение

        Route::post('restore', [ClientController::class, 'bulkRestore'])
            ->name('restore')
            ->middleware('permission:clients.restore'); // отдельное разрешение
    });

    // Операции над одной сущностью с параметром {client}
    Route::prefix('clients/{client}')->name('clients.')->group(function () {
        // Force delete одной записи
        Route::delete('force', [ClientController::class, 'forceDelete'])
            ->name('force-delete')
            ->middleware('permission:clients.force-delete');

        // Restore одной записи
        Route::post('restore', [ClientController::class, 'restore'])
            ->name('restore-single') // уникальное имя, чтобы не конфликтовать с bulk.restore
            ->middleware('permission:clients.restore');

        // Avatar
        Route::put('avatar', [ClientController::class, 'avatar'])
            ->name('avatar')
            ->middleware('permission:clients.edit');
    });

    // Вспомогательные роуты без параметров
    Route::get('/clients/form-meta', [ClientController::class, 'formMeta'])
        ->name('clients.form-meta')
        ->middleware('permission:clients.view');

    // Архив (не часть resource)
    Route::get('/clients/archive', [ClientController::class, 'archive'])
        ->name('clients.archive')
        ->middleware('permission:clients.view');

    // 2. В конце — resource, чтобы он «добирал» оставшиеся стандартные пути
    Route::resource('clients', ClientController::class)
        ->middleware([
                         'permission:clients.view',      // index, show
                         'permission:clients.create',    // create, store
                         'permission:clients.edit',       // edit, update
                         'permission:clients.delete',    // destroy (soft)
                     ])
        ->withTrashed(['show']); // show работает и с trashed-записями
});
