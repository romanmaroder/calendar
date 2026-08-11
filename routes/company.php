<?php


use App\Http\Controllers\CompanyController;

Route::middleware(['auth'])->group(function () {
    // --- Кастомные роуты (всегда ПЕРЕД resource) ---

    Route::get('/company/form-meta', [CompanyController::class, 'formMeta'])
        ->name('company.form-meta')
        ->middleware('permission:companies.view');

    Route::get('/company/archive', [CompanyController::class, 'archive'])
        ->name('company.archive')
        ->middleware('permission:companies.delete');

    // Аватар
    Route::put('/avatar/{company}', [CompanyController::class, 'avatar'])
        ->name('company.avatar')
        ->middleware('permission:companies.edit');

    // Soft delete (одна запись)
    Route::delete('/company/{id}', [CompanyController::class, 'softDelete'])
        ->name('company.soft.delete')
        ->middleware('permission:companies.delete');

    // Bulk soft delete
    Route::delete('/company/bulk/soft', [CompanyController::class, 'bulkSoftDelete'])
        ->name('company.bulk.soft')
        ->middleware('permission:companies.delete');

    // Force delete (одна запись)
    Route::delete('/company/{id}/force', [CompanyController::class, 'forceDelete'])
        ->name('company.force')
        ->middleware('permission:companies.force-delete');

    // Bulk force delete
    Route::delete('/company/{ids}/bulk/force', [CompanyController::class, 'bulkForceDelete'])
        ->name('company.bulk.force')
        ->middleware('permission:companies.force-delete');

    // Restore (одна запись)
    Route::post('/company/{id}/restore', [CompanyController::class, 'restore'])
        ->name('company.restore')
        ->middleware('permission:companies.restore');

    // Bulk restore
    Route::post('/company/{ids}/bulk/restore', [CompanyController::class, 'bulkRestore'])
        ->name('company.bulk.restore')
        ->middleware('permission:companies.restore');


    // 4. Resource — в самом конце, чтобы не «перехватывать» кастомные пути
    Route::resource('company', CompanyController::class)
        ->only(['index', 'create', 'store', 'edit', 'update', 'show'])
        ->middlewareFor('index', ['permission:companies.view'])
        ->middlewareFor('show', ['permission:companies.view'])
        ->middlewareFor(['create', 'store'], ['permission:companies.create'])
        ->middlewareFor(['edit', 'update'], ['permission:companies.edit'])
        ->withTrashed(['show']); // show работает и с trashed-записями



});