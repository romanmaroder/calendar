<?php

use App\Http\Controllers\Client\ClientController;

Route::middleware(['auth'])->group(function () {
    Route::put('clients/restore/{id}',[ClientController::class,'restore'])->name('clients.restore');
    Route::put('clients/multiRestore/{ids}',[ClientController::class,'multiRestore'])->name('multiRestore');
    Route::put('/avatar/{client}',[ClientController::class,'avatar'])->name('avatar');
    Route::resource('clients', ClientController::class);
    Route::delete('clients/trash/{ids}', [ClientController::class, 'trash'])->name('trash');
    Route::delete('clients/multiDestroy/{ids}', [ClientController::class, 'multiDestroy'])->name('multiDestroy');
    Route::get('/archive',[ClientController::class,'archive'])->name('clients.archive');
});



