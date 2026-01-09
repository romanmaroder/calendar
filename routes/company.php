<?php


use App\Http\Controllers\CompanyController;

Route::middleware(['auth'])->group(function () {
    Route::get('/company/archive', [CompanyController::class, 'archive'])->name('company.archive');
    Route::put('/avatar/{company}', [CompanyController::class, 'avatar'])->name('company.avatar');
// Soft delete (один/несколько)
    Route::delete('/company/bulk/soft', [CompanyController::class, 'bulkSoftDelete'])->name('company.bulk.soft');
    Route::delete('/company/{id}', [CompanyController::class, 'softDelete'])->name('company.soft.delete');
// Force delete (один/несколько)
    Route::delete('/company/bulk/force', [CompanyController::class, 'bulkForceDelete'])->name('company.bulk.force');
    Route::delete('/company/{id}/force', [CompanyController::class, 'forceDelete'])->name('company.force');
// Restore (один/несколько)
    Route::post('/company/bulk/restore', [CompanyController::class, 'bulkRestore'])->name('company.bulk.restore');
    Route::post('/company/{id}/restore', [CompanyController::class, 'restore'])->name('company.restore');
    Route::resource('company', CompanyController::class)->withTrashed(['show']);
});