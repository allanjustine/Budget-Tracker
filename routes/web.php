<?php

use App\Http\Controllers\AccountWalletController;
use App\Http\Controllers\BankTypeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\LoanTypeController;
use App\Http\Controllers\LoanWalletController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware(['auth', 'verified', 'role:user'])->group(fn() => [
    Route::resource('account-wallets', AccountWalletController::class),

    Route::resource('loans', LoanWalletController::class),

    Route::resource('expenses', ExpenseController::class),

    Route::controller(DashboardController::class)->group(fn() => [
        Route::get('dashboard', 'index')->name('dashboard'),
    ]),

]);

Route::middleware(['auth', 'verified', 'role:admin'])->group(fn() => [
    Route::resource('bank-types', BankTypeController::class),

    Route::resource('loan-types', LoanTypeController::class),

    Route::resource('expense-categories', ExpenseCategoryController::class),

]);


require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
