<?php

use App\Http\Controllers\InstallerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\v1\AuthController;
use App\Http\Controllers\v1\UserController;
use App\Http\Controllers\v1\IndexController;
use App\Http\Controllers\test\TestController;
use App\Http\Controllers\v1\ProductsController;
use App\Http\Controllers\v1\CustomersController;
use App\Http\Controllers\v1\SuppliersController;
use App\Http\Controllers\v1\EditInvoicesController;
use App\Http\Controllers\v1\CustomerOrdersController;
use App\Http\Controllers\v1\SupplierOrdersController;
use App\Http\Controllers\v1\CustomersInvoiceController;
use App\Http\Controllers\v1\InvoiceGeneratorController;
use App\Http\Controllers\v1\SuppliersInvoiceController;
use App\Http\Controllers\v1\createCustomerOrderController;
use App\Http\Controllers\v1\CreateSupplierOrderController;
use App\Http\Controllers\v1\ProductStatsController;
use App\Http\Controllers\v1\SettingsController;

Route::get('/test', [TestController::class, 'test']);
Route::controller(InstallerController::class)->group(function () {
    Route::redirect('/install', '/install/step-1');
    Route::view('/install/step-1', 'install.step_one', ['title' =>  'INSTALLER-STEP 1'])->name('installer.step1');
    Route::post('/install/step-1', 'stepOne');
    Route::view('/install/step-2', 'install.step_two', ['title' =>  'INSTALLER-STEP 2'])->name('installer.step2');
    Route::post('/install/step-2', 'stepTwo');
    Route::view('/install/final', 'install.final', ['title' =>  'INSTALLER-STEP 3'])->name('installer.step3');
    Route::post('/install/final', 'stepThree');
});
Route::middleware(['web', 'app'])->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::get('/login', 'login')->name('login');
        Route::post('/login', 'verify_user');
        Route::view('/auth/forgot-password', 'auth.forgot_password', ['title' => 'FORGOT PASSWORD'])->name('forgot.password');
        Route::view('/auth/new-password', 'auth.new_password', ['title' => 'RESET PASSWORD'])->name('new.password');
        Route::post('/auth/forgot-password', 'forgotPassword');
        Route::post('/auth/new-password', 'ResetPassword');
        Route::post('/logout', 'user_logout')->name('logout');
    })->middleware('guest');

    // only authorize users can access these routes--admin
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::controller(AuthController::class)->group(function () {
            Route::post('/update-profile', 'store');
            Route::post('/auth/change-password', 'update');
        });
        Route::controller(IndexController::class)->group(function () {
            Route::redirect('/', '/dashboard');
            Route::get('/dashboard', 'index')->name('dashboard');
        });
        Route::controller(CustomerOrdersController::class)->group(function () {
            Route::get('/orders/customers', 'index')->name('orders.customers');
            // orders return by worker
            Route::get('/order/returns', 'show')->name('order.returns');
            Route::post('/orders/customers/return/{order_id}', 'update');
            Route::post('/orders/customers/reset/{order_id}', 'destroy');
        });
        Route::controller(SupplierOrdersController::class)->group(function () {
            Route::get('/orders/suppliers', 'index')->name('orders.suppliers');
        });
        // all resource routes//
        Route::resource('/user/admin', UserController::class);
        Route::controller(InvoiceGeneratorController::class)->prefix('view-file')->group(function () {
            Route::get('/customer/token/{token}', 'invoice')->name('view-file.invoice');
            Route::get('/supplier/token/{token}', 'invoice_supplier')->name('view-file.invoice.supplier');
            Route::get('/returns/token/{token}', 'returns')->name('view-file.returns');
            Route::get('/open-stock', 'open_stock')->name('view-file.open-stock');
        });
        Route::controller(EditInvoicesController::class)->prefix('orders')->group(function () {
            Route::get('/edit/query', 'edit')->name('order.edit');
            Route::post('/edit/query', 'store')->name('order.edit.save');
            Route::get('/edit', 'index');
            Route::delete('/edit/delete', 'destroy');
        });
        Route::controller(ProductsController::class)->group(function () {
            Route::delete('/products/delete', 'destroy');
            Route::get('/product/{name}', 'fetch');
        });
        Route::resource('/products', ProductsController::class);
        Route::resource('/stats/product', ProductStatsController::class);
        Route::resource('/customers', CustomersController::class);
        Route::resource('/suppliers', SuppliersController::class);
        Route::resource('/create-order/customer', createCustomerOrderController::class);
        Route::resource('/create-order/supplier', CreateSupplierOrderController::class);
        Route::controller(CustomersInvoiceController::class)->group(function () {
            Route::get('/invoices/customers', 'index')->name('invoices.customers');
        });
        Route::controller(SuppliersInvoiceController::class)->group(function () {
            Route::get('/invoices/suppliers', 'index')->name('invoices.suppliers');
        });
        Route::controller(SettingsController::class)->group(function () {
            Route::get('/settings', 'index')->name('settings');
        });
    });
});
