<?php

use App\Http\Controllers\User\UserController;

Route::middleware(['auth'])->group(function () {
    Route::get('/users/form-meta', [UserController::class, 'formMeta']);
    //Route::put('/users/{user}/roles', [UserController::class, 'assignRoles'])->name('users.roles.assign');


    Route::redirect('users', '/users/index');

    // Просмотр доступен тем, у кого users.view
    Route::get('users/archive', [UserController::class, 'archive'])->name('users.archive')->middleware('permission:users.view');
    Route::get('/users', [UserController::class, 'index'])->name('users')->middleware('permission:users.view');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create')->middleware('permission:users.create');

    // Только те, у кого есть permission:users.create, смогут делать POST
    Route::post('/users', [UserController::class, 'store'])->name('users.store')->middleware('permission:users.create');

    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit')->middleware('permission:users.edit');

    // Только с правом edit могут делать PUT/PATCH
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update')->middleware('permission:users.edit');
    Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update')->middleware('permission:users.edit');;


    // Просмотр доступен тем, у кого users.view
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show')->withTrashed();
    Route::put('/avatar/{user}', [UserController::class, 'avatar'])->name('users.avatar');

    // Только с правом delete могут удалять
    // Soft delete (один/несколько)
    Route::delete('/users/bulk/soft', [UserController::class, 'bulkSoftDelete'])->name('users.bulk.soft')->middleware('permission:users.delete');
    Route::delete('/users/{id}', [UserController::class, 'softDelete'])->name('users.soft.delete')->middleware('permission:users.delete');

    // Force delete (один/несколько)
    Route::delete('/users/bulk/force', [UserController::class, 'bulkForceDelete'])->name('users.bulk.force')->middleware('permission:users.delete');
    Route::delete('/users/{id}/force', [UserController::class, 'forceDelete'])->name('users.force')->middleware('permission:users.delete');

    // Restore (один/несколько)
    Route::post('/users/bulk/restore', [UserController::class, 'bulkRestore'])->name('users.bulk.restore');
    Route::post('/users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
});

