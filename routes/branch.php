<?php

use App\Http\Controllers\BranchController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    // --- Кастомные роуты (всегда ПЕРЕД resource) ---

    Route::get('/branch/form-meta', [BranchController::class, 'formMeta'])
        ->name('branch.form-meta')
        ->middleware('permission:branches.view');

    Route::get('/branch/archive', [BranchController::class, 'archive'])
        ->name('branch.archive')
        ->middleware('permission:branches.delete');

    // Аватар
    Route::put('/branch/{branch}/avatar', [BranchController::class, 'avatar'])
        ->name('branch.avatar')
        ->middleware('permission:branches.edit');

    // Отписка пользователей
    Route::post('/branch/{branch}/unsubscribe-users', [BranchController::class, 'unsubscribeUsers'])
        ->name('branch.unsubscribe-users')
        ->middleware('permission:branches.edit');

    // Soft delete (одна запись)
    Route::delete('/branch/{id}', [BranchController::class, 'softDelete'])
        ->name('branch.soft.delete')
        ->middleware('permission:branches.delete');

    // Bulk soft delete
    Route::delete('/branch/bulk/soft', [BranchController::class, 'bulkSoftDelete'])
        ->name('branch.bulk.soft')
        ->middleware('permission:branches.delete');

    // Force delete (одна запись)
    Route::delete('/branch/{id}/force', [BranchController::class, 'forceDelete'])
        ->name('branch.force')
        ->middleware('permission:branches.force-delete');

    // Bulk force delete
    Route::delete('/branch/{ids}/bulk/force', [BranchController::class, 'bulkForceDelete'])
        ->name('branch.bulk.force')
        ->middleware('permission:branches.force-delete');

    // Restore (одна запись)
    Route::post('/branch/{id}/restore', [BranchController::class, 'restore'])
        ->name('branch.restore')
        ->middleware('permission:branches.restore');

    // Bulk restore
    Route::post('/branch/{ids}/bulk/restore', [BranchController::class, 'bulkRestore'])
        ->name('branch.bulk.restore')
        ->middleware('permission:branches.restore');

    // 4. Resource — в самом конце, чтобы не «перехватывать» кастомные пути
    Route::resource('branch', BranchController::class)
        ->only(['index', 'create', 'store', 'edit', 'update', 'show'])
        ->middlewareFor('index', ['permission:branches.view'])
        ->middlewareFor('show', ['permission:branches.view'])
        ->middlewareFor(['create', 'store'], ['permission:branches.create'])
        ->middlewareFor(['edit', 'update'], ['permission:branches.edit'])
        ->withTrashed(['show']);
});
