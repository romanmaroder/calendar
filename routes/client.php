<?php

use App\Http\Controllers\Client\ClientController;

Route::get('/clients', [ClientController::class,'index'] )->name('clients');
Route::get('/clients/create',[ClientController::class,'create'] )->name('clients.create');
Route::post('/clients',[ClientController::class,'store'] )->name('clients.store');
Route::get('/clients/{client}',[ClientController::class,'show'] )->name('clients.show');
Route::get('/clients/{client}/edit',[ClientController::class,'edit'] )->name('clients.edit');
Route::put('/clients/{client}',[ClientController::class,'update'] )->name('clients.update');
Route::delete('/clients/{client}',[ClientController::class,'destroy'] )->name('clients.destroy');