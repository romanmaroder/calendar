<?php

use App\Http\Controllers\Client\ClientController;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;

Route::middleware(['auth'])->group(function () {
    Route::redirect('clients', '/clients/index');

    Route::get('archive', [ClientController::class, 'archive'])->name('archive');
    Route::get('clients/index', [ClientController::class, 'index'])->name('clients.index');
    Route::post('clients/index', [ClientController::class, 'store'])->name('clients.store');
    Route::get('clients/{client}/edit', [ClientController::class, 'edit'])->name('clients.edit');
    Route::put('clients/{client}', [ClientController::class, 'update'])->name('clients.update');
    Route::put('clients/restore/{id}',[ClientController::class,'restore'])->name('clients.restore');
    Route::put('clients/multiRestore/{ids}',[ClientController::class,'multiRestore'])->name('multiRestore');
    Route::put('/avatar/{client}', [ClientController::class, 'avatar'])->name('avatar');
    Route::delete('clients/trash/{ids}', [ClientController::class, 'trash'])->name('clients.trash');
    Route::delete('clients/multiDestroy/{ids}', [ClientController::class, 'multiDestroy'])->name('multiDestroy');
    Route::delete('clients/destroy/{client}', [ClientController::class, 'destroy'])->name('clients.destroy');

});


