<?php

use Illuminate\Support\Facades\Route;


//Route::get('/borrows/export', [BorrowExportController::class, 'export'])->name('borrows.export');

Route::get('/', function () {
    return view('welcome');
});
