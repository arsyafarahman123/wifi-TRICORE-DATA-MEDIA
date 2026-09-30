<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\CustomerManagementController;
use App\Http\Controllers\CustomerPortalController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceManagementController;
use App\Http\Controllers\PackageManagementController;
use App\Http\Controllers\PartnerDashboardController;
use Illuminate\Support\Facades\Route;

// Public Landing Page & Company Profile
Route::get('/', [HomeController::class, 'index'])->name('home');

// Customer Self-Service & Registration
Route::post('/cek-tagihan', [CustomerPortalController::class, 'checkBill'])->name('bill.check');
Route::get('/tagihan/{code}', [CustomerPortalController::class, 'viewBill'])->name('bill.view');
Route::post('/daftar-paket', [CustomerPortalController::class, 'register'])->name('register.submit');
Route::post('/cek-coverage', [CustomerPortalController::class, 'checkCoverage'])->name('coverage.check');

// Interactive Virtual Assistant / Chatbot API
Route::post('/chatbot/message', [ChatbotController::class, 'sendMessage'])->name('chatbot.message');

// Authentication (Admin & Mitra)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Portal for Mitra & Admin
Route::prefix('portal')->name('portal.')->middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/', [PartnerDashboardController::class, 'index'])->name('dashboard');

    // Customers
    Route::get('/customers', [CustomerManagementController::class, 'index'])->name('customers.index');
    Route::get('/customers/create', [CustomerManagementController::class, 'create'])->name('customers.create');
    Route::post('/customers', [CustomerManagementController::class, 'store'])->name('customers.store');
    Route::get('/customers/{customer}', [CustomerManagementController::class, 'show'])->name('customers.show');
    Route::get('/customers/{customer}/edit', [CustomerManagementController::class, 'edit'])->name('customers.edit');
    Route::put('/customers/{customer}', [CustomerManagementController::class, 'update'])->name('customers.update');
    Route::post('/customers/{customer}/toggle-status', [CustomerManagementController::class, 'toggleStatus'])->name('customers.toggle-status');
    Route::delete('/customers/{customer}', [CustomerManagementController::class, 'destroy'])->name('customers.destroy');

    // Invoices & Billing
    Route::get('/invoices', [InvoiceManagementController::class, 'index'])->name('invoices.index');
    Route::post('/invoices/generate-monthly', [InvoiceManagementController::class, 'generateMonthly'])->name('invoices.generate-monthly');
    Route::post('/invoices/{invoice}/pay', [InvoiceManagementController::class, 'markAsPaid'])->name('invoices.pay');
    Route::get('/invoices/{invoice}/print', [InvoiceManagementController::class, 'print'])->name('invoices.print');

    // Packages
    Route::get('/packages', [PackageManagementController::class, 'index'])->name('packages.index');
    Route::post('/packages', [PackageManagementController::class, 'store'])->name('packages.store');
    Route::put('/packages/{package}', [PackageManagementController::class, 'update'])->name('packages.update');
    Route::post('/packages/{package}/toggle', [PackageManagementController::class, 'toggle'])->name('packages.toggle');
    Route::delete('/packages/{package}', [PackageManagementController::class, 'destroy'])->name('packages.destroy');
});
