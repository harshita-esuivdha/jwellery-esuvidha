<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SuperadminController;
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
