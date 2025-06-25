<?php

use App\Http\Controllers\AccountWalletController;
use App\Http\Controllers\BankTypeController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\LoanTypeController;
use App\Http\Controllers\LoanWalletController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(fn() => [
    Route::resource('bank-types', BankTypeController::class),

    Route::resource('loan-types', LoanTypeController::class),

    Route::resource('expense-categories', ExpenseCategoryController::class),

    Route::resource('account-wallets', AccountWalletController::class),

    Route::resource('loan-wallets', LoanWalletController::class),

    Route::resource('expenses', ExpenseController::class),

]);


require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
