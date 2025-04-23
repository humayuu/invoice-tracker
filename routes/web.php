<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\InvoiceController;



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

// ----------------------------- Clients All Routes Ends Here --------------------------------------//
