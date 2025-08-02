<?php
use App\Http\Controllers\User\UserController;


Route::middleware(['auth'])->group(function () {
    Route::get('/users',[UserController::class,'index'] )->name('users');
    Route::get('/users/create',[UserController::class,'create'] )->name('users.create');
    Route::post('/users',[UserController::class,'store'] )->name('users.store');
    Route::get('users/archive',[UserController::class,'archive'])->name('users.archive');
    Route::get('/users/{user}/edit',[UserController::class,'edit'] )->name('users.edit');
    Route::put('/users/{user}',[UserController::class,'update'] )->name('users.update');
    Route::put('users/restore/{id}',[UserController::class,'restore'])->name('users.restore');
    Route::put('users/multiRestore/{ids}',[UserController::class,'multiRestore'])->name('multiRestore');
    Route::delete('users/trash/{ids}', [UserController::class, 'trash'])->name('trash');
    Route::delete('users/multiDestroy/{ids}', [UserController::class, 'multiDestroy'])->name('multiDestroy');
    Route::delete('/users/destroy/{user}',[UserController::class,'destroy'] )->name('users.destroy');
});

