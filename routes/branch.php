<?php

use App\Http\Controllers\BranchController;


Route::get('/branch/archive', [BranchController::class, 'archive'])->name('branch.archive');
Route::put('/avatar/{branch}', [BranchController::class, 'avatar'])->name('branch.avatar');
// Soft delete (один/несколько)
Route::delete('/branch/bulk/soft', [BranchController::class, 'bulkSoftDelete'])->name('branch.bulk.soft');
Route::delete('/branch/{id}', [BranchController::class, 'softDelete'])->name('branch.soft.delete');
// Force delete (один/несколько)
Route::delete('/branch/bulk/force', [BranchController::class, 'bulkForceDelete'])->name('branch.bulk.force');
Route::delete('/branch/{id}/force', [BranchController::class, 'forceDelete'])->name('branch.force');
// Restore (один/несколько)
Route::post('/branch/bulk/restore', [BranchController::class, 'bulkRestore'])->name('branch.bulk.restore');
Route::post('/branch/{id}/restore', [BranchController::class, 'restore'])->name('branch.restore');

Route::post('branches/{branch}/unsubscribe', [BranchController::class, 'unsubscribeUsers'])->name('branch.unsubscribe');

Route::resource('branch', BranchController::class)->withTrashed(['show']);

