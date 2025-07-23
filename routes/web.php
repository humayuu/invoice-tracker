<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PurchaseController;


Route::get('/', function () {
    return redirect()->route('invoice.all');
});


// ----------------------------- Invoice All Routes Starts Here --------------------------------------//

Route::name('invoice.')->group(function () {
    Route::controller(InvoiceController::class)->group(function () {

        Route::get('/invoice/all', 'invoiceAll')->name('all');
        Route::get('/invoice/add', 'invoiceAdd')->name('add');
        Route::get('/invoice/edit/{id}', 'invoiceEdit')->name('edit');
        Route::get('/invoice/delete/{id}', 'invoiceDelete')->name('delete');
        Route::get('/invoice/paid/{id}', 'invoicePaid')->name('paid');
        Route::get('/invoice/check-duplicate', 'checkDuplicateInvoice')->name('check.duplicate');
        Route::get('/invoice/check-overdue', 'checkOverdueInvoices')->name('check.overdue');
        Route::get('/invoice/detail/{id}', 'invoiceDetail')->name('detail');

        Route::post('/invoice/store', 'invoiceStore')->name('store');
        Route::post('/invoice/update', 'invoiceUpdate')->name('update');
    });
});

// ----------------------------- Invoice All Routes Ends Here --------------------------------------//


// ----------------------------- Clients All Routes Starts Here --------------------------------------//

Route::name('clients.')->group(function () {
    Route::controller(ClientController::class)->group(function () {
        Route::get('/client/all', 'clientAll')->name('all');
        Route::get('/client/add', 'clientAdd')->name('add');
        Route::get('/client/edit/{id}', 'clientEdit')->name('edit');
        Route::get('/client/delete/{id}', 'clientDelete')->name('delete');
        Route::get('/client/{id}/invoices', 'clientWiseView')->name('client.wise.view');
        Route::get('/client/{id}/invoice-pdf', 'generateInvoicePDF')->name('client.invoice.pdf');
        Route::get('/client/summary-report', 'generateSummaryReport')->name('summary.report');

        Route::post('/client/store', 'clientStore')->name('store');
        Route::post('/client/update', 'clientUpdate')->name('update');
    });
});

Route::post('/clients/{client}/payments', [App\Http\Controllers\ClientController::class, 'storePayment'])->name('clients.payments.store');
Route::post('/clients/{client}/payments/clear', [App\Http\Controllers\ClientController::class, 'clearClientPayments'])->name('clients.payments.clear');

// ----------------------------- Clients All Routes Ends Here --------------------------------------//

// ----------------------------- Suppliers All Routes Starts Here --------------------------------------//

Route::name('suppliers.')->group(function () {
    Route::controller(SupplierController::class)->group(function () {
        Route::get('/supplier/all', 'supplierAll')->name('all');
        Route::get('/supplier/add', 'supplierAdd')->name('add');
        Route::get('/supplier/edit/{id}', 'supplierEdit')->name('edit');
        Route::get('/supplier/delete/{id}', 'supplierDelete')->name('delete');
        Route::post('/supplier/store', 'supplierStore')->name('store');
        Route::post('/supplier/update', 'supplierUpdate')->name('update');
    });
});

Route::get('/supplier/{id}/purchases-report', [App\Http\Controllers\PurchaseController::class, 'supplierWiseView'])->name('suppliers.purchases.report');
Route::get('/supplier/{id}/purchase-pdf', [App\Http\Controllers\PurchaseController::class, 'generateSupplierPDF'])->name('suppliers.purchase.pdf');
Route::post('/suppliers/{supplier}/payments', [App\Http\Controllers\PurchaseController::class, 'storeSupplierPayment'])->name('suppliers.payments.store');
Route::post('/suppliers/{supplier}/payments/clear', [App\Http\Controllers\PurchaseController::class, 'clearSupplierPayments'])->name('suppliers.payments.clear');

// ----------------------------- Suppliers All Routes Ends Here --------------------------------------//

// ----------------------------- Purchases All Routes Starts Here --------------------------------------//

Route::name('purchase.')->group(function () {
    Route::controller(PurchaseController::class)->group(function () {
        Route::get('/purchase/all', 'purchaseAll')->name('all');
        Route::get('/purchase/add', 'purchaseAdd')->name('add');
        Route::get('/purchase/edit/{id}', 'purchaseEdit')->name('edit');
        Route::get('/purchase/delete/{id}', 'purchaseDelete')->name('delete');
        Route::get('/purchase/paid/{id}', 'purchasePaid')->name('paid');
        Route::get('/purchase/summary-report', 'generateSummaryReport')->name('summary.report');
        Route::get('/purchase/detail/{id}', 'purchaseDetail')->name('detail');

        Route::post('/purchase/store', 'purchaseStore')->name('store');
        Route::post('/purchase/update', 'purchaseUpdate')->name('update');
    });
});

// ----------------------------- Purchases All Routes Ends Here --------------------------------------//
