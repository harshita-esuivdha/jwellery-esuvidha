<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SuperadminController;
use App\Http\Controllers\MetalRateController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\CreatePurchasesTable;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('superadmin')->group(function () {
    Route::get('/profile', [SuperadminController::class, 'profile'])->name('superadmin.profile');
    Route::put('/profile/update', [SuperadminController::class, 'updateProfile'])->name('superadmin.profile.update');
});
Route::get('/superadmin/register', [SuperadminController::class, 'showRegisterForm'])->name('superadmin.register.login');
 Route::put('/update/{id}', [SuperadminController::class, 'update'])->name('superadmin.update');
    Route::delete('/destroy/{id}', [SuperadminController::class, 'destroy'])->name('superadmin.destroy');
Route::post('/superadmin/register', [SuperadminController::class, 'register'])->name('superadmin.register.store');
Route::get('superadmin/dashboard', [SuperadminController::class, 'dashboard'])
    ->name('superadmin.dashboard');
// Update expiry date
Route::put('superadmin/update-expiry/{id}', [SuperadminController::class, 'updateExpiry'])
    ->name('superadmin.updateExpiry');

Route::put('superadmin/update-password/{id}', [SuperadminController::class, 'updatePassword'])
    ->name('superadmin.updatePassword');

Route::get('/', [CompanyController::class, 'create'])->name('company.register');
Route::post('/register', [CompanyController::class, 'store'])->name('company.store');
// Show Login Form
Route::get('/login', [CompanyController::class, 'showLoginForm'])->name('company.login');

// Handle Login Form Submission
Route::post('/login', [CompanyController::class, 'login'])->name('company.login.submit');
Route::get('/company/profile', [CompanyController::class, 'createprofile'])
    ->name('Company.profile');

// Store or update company details
Route::post('/company/profile', [CompanyController::class, 'storeprofile'])
    ->name('company.storeprofile');
// Logout
Route::post('/logout', [CompanyController::class, 'logout'])->name('company.logout');

// Dashboard (protected)
Route::get('/dashboard', [CompanyController::class, 'dashboard'])
    ->name('dashboard');

// Public companies listing (no login required)
Route::get('/companies', [CompanyController::class, 'publicList'])->name('companies.public');
Route::delete('/company/{id}', [CompanyController::class, 'destroy'])->name('company.destroy');
Route::get('/sub/dashboard', [CompanyController::class, 'subDashboard'])
    ->name('sub.dashboard');
    Route::prefix('admin')->group(function () {
    Route::resource('customers', CustomerController::class)->names('admin.customers');
});
Route::get('admin/customers/{id}/analysis', [CustomerController::class, 'analysis'])
    ->name('admin.customers.analysis');
Route::prefix('company')->group(function () {
    // Store or update rates
    Route::post('/dashboard-analysis', [MetalRateController::class, 'store'])
        ->name('dashboard.analysis.store');

    // Fetch rates for selected date (AJAX)
   Route::get('/dashboard-analysis/{date}', [MetalRateController::class, 'fetchRates'])
     ->name('dashboard.analysis.get');// ✅ give this a name
});
Route::get('/customer', [CustomerController::class, 'index'])
    ->name('company.customers.index');


Route::prefix('items')->group(function () {
    Route::get('/', [ItemController::class, 'index'])->name('items.index');      // View
    Route::get('/create', [ItemController::class, 'create'])->name('items.create'); // Add
    Route::post('/store', [ItemController::class, 'store'])->name('items.store');   // Store
    Route::get('/edit/{id}', [ItemController::class, 'edit'])->name('items.edit');  // Edit
Route::put('items/{id}', [ItemController::class, 'update'])->name('items.update');

    Route::delete('/delete/{id}', [ItemController::class, 'destroy'])->name('items.destroy'); // Delete
});

  // Show create bill page
Route::get('/billing/create/{id?}', [BillController::class, 'create'])->name('billing.create');

// Show all bills page
Route::get('/billing', [BillController::class, 'create'])->name('billing.index');

// AJAX: fetch item rate
Route::get('/billing/items/{item}', [BillController::class, 'getItemRate'])->name('billing.getItemRate');

// Store bill


Route::get('/metal-rates/latest', [MetalRateController::class, 'latest']);

Route::post('/invoices/store', [BillController::class, 'store'])->name('invoices.store');
// routes/web.php
Route::get('/invoices/history', [BillController::class, 'history'])->name('invoices.history');

Route::delete('/invoices/{id}', [BillController::class, 'destroy'])->name('invoices.destroy');
Route::get('/get-invoice/{id}', function ($id) {
    return DB::table('invoices')->where('id', $id)->first();
});


Route::get('/purchases', [CreatePurchasesTable::class, 'index'])->name('purchases.index');
Route::get('/purchases/create', [CreatePurchasesTable::class, 'create'])->name('purchases.create');
Route::post('/purchases/store', [CreatePurchasesTable::class, 'store'])->name('purchases.store');
Route::get('/purchases/{id}/edit', [CreatePurchasesTable::class, 'edit'])->name('purchases.edit');
Route::put('/purchases/{id}', [CreatePurchasesTable::class, 'update'])->name('purchases.update');
Route::delete('/purchases/{id}', [CreatePurchasesTable::class, 'destroy'])->name('purchases.destroy');

Route::post('/invoices/save-image', [BillController::class, 'saveImage'])->name('invoices.saveImage');
Route::resource('invoices', BillController::class);




