<?php

use App\Http\Controllers\v1\AuthController;
use App\Http\Controllers\v1\createCustomerOrderController;
use App\Http\Controllers\v1\CreateSupplierOrderController;
use App\Http\Controllers\v1\CustomerOrdersController;
use App\Http\Controllers\v1\CustomersController;
use App\Http\Controllers\v1\CustomersInvoiceController;
use App\Http\Controllers\v1\EditInvoicesController;
use App\Http\Controllers\v1\IndexController;
use App\Http\Controllers\v1\InvoiceGeneratorController;
use App\Http\Controllers\v1\ProductsController;
use App\Http\Controllers\v1\SupplierOrdersController;
use App\Http\Controllers\v1\SuppliersController;
use App\Http\Controllers\v1\SuppliersInvoiceController;
use App\Http\Controllers\v1\UserController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'login')->name('login');
    Route::post('/login', 'verify_user');
    Route::post('/auth/login', 'verify_login');
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
Route::controller(EditInvoicesController::class)->prefix('invoices')->group(function () {
    Route::get('/edit/query', 'edit')->name('order.edit');
    Route::get('/edit', 'index');
    Route::delete('/edit/delete', 'destroy');
});
Route::controller(ProductsController::class)->group(function(){
    Route::delete('/products/delete', 'destroy');
    Route::get('/product/{name}', 'fetch');

});
Route::resource('/products', ProductsController::class);
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
