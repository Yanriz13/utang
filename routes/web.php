<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DebtController;
use App\Http\Controllers\DebtPaymentController;
use App\Http\Controllers\MonthlyIncomeController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', [DebtController::class, 'index']);

    Route::resource('debts', DebtController::class);

    Route::post(
        '/debts/{id}/pay',
        [DebtPaymentController::class, 'store']
    );

    Route::resource('monthly-income', MonthlyIncomeController::class);
    Route::get('/export-excel', [DebtController::class, 'exportExcel']);
    Route::delete('/payments/{id}', [DebtController::class, 'deletePayment']);
    Route::post('/debts/store-multiple', [DebtController::class, 'storeMultiple']);
    Route::post('/debts/bulk-delete', [DebtController::class, 'bulkDelete']);
    Route::post('/payments/bulk-delete', [DebtController::class, 'bulkDeletePayments']);
    Route::get('/download-template', [DebtController::class, 'downloadTemplate']);
    Route::post('/import-excel', [DebtController::class, 'importExcel']);
});