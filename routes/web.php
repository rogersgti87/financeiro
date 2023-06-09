<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebHookController;
use App\Http\Controllers\Backend\HomeController;
use App\Http\Controllers\Backend\CustomerController;
use App\Http\Controllers\Backend\CustomerServicesController;
use App\Http\Controllers\Backend\InvoicesController;
use App\Http\Controllers\Backend\ServicesActivesController;
use App\Http\Controllers\Backend\ServiceController;
use App\Http\Controllers\Backend\ReportsController;
use App\Http\Controllers\Backend\ConfigController;
use App\Http\Controllers\Backend\PayableController;
use App\Http\Controllers\Backend\BackupController;


Route::prefix('webhook')->group(function () {
    Route::post('sendpulse-smtp', [WebHookController::class,'index']);
    Route::post('paghiper', [WebHookController::class,'paghiper']);
    Route::post('mercadopago', [WebHookController::class,'mercadopago']);
    Route::get('backups/{user}', [WebHookController::class,'backups']);
    Route::post('whatsapp-messages', [WebHookController::class,'whatsappmessage']);
});

/************************/
// Routes Backend
Route::middleware('auth')->group(function () {
  Route::get('/',[HomeController::class ,'index'])->name('backend.home');

  //Customers
  Route::get('/customers', [CustomerController::class,'index'])->name('backend.customers');
  Route::get('/customers-create', [CustomerController::class,'create'])->name('backend.customers.create');
  Route::post('/customers-store', [CustomerController::class,'store'])->name('backend.customers.store');
  Route::get('/customers-edit/{id}', [CustomerController::class,'edit'])->name('backend.customers.edit');
  Route::put('/customers-update/{id}', [CustomerController::class,'update'])->name('backend.customers.update');
  Route::get('/customers-details/{id}', [CustomerController::class,'show'])->name('backend.customers.show');
  Route::get('/customers-services/{id}', [CustomerController::class,'showServices'])->name('backend.customers.showServices');
  Route::get('/customers-invoices/{id}', [CustomerController::class,'showInvoices'])->name('backend.customers.showInvoices');
  Route::get('/customers-activities/{id}', [CustomerController::class,'showActivities'])->name('backend.customers.showActivities');
  Route::delete('/customers-delete', [CustomerController::class,'destroy'])->name('backend.customers.delete');

  //Customer Services
  Route::get('/customerservices-create/{customer_id}', [CustomerServicesController::class,'create'])->name('backend.customerservices.create');
  Route::post('/customerservices-store', [CustomerServicesController::class,'store'])->name('backend.customerservices.store');
  Route::get('/customerservices-edit/{customer_id}/{id}', [CustomerServicesController::class,'edit'])->name('backend.customerservices.edit');
  Route::put('/customerservices-update/{id}', [CustomerServicesController::class,'update'])->name('backend.customerservices.update');
  Route::get('/customerservices-details/{id}', [CustomerServicesController::class,'show'])->name('backend.customerservices.show');
  Route::delete('/customerservices-delete', [CustomerServicesController::class,'destroy'])->name('backend.customerservices.delete');
  Route::get('/customerservices-generatepixpayment/{invoice_id}', [CustomerServicesController::class,'generatePixPayment'])->name('backend.customerservices.generatepixpayment');
  Route::get('/customerservices-generatebilletpayment/{invoice_id}', [CustomerServicesController::class,'generateBilletPayment'])->name('backend.customerservices.generatebilletpayment');
  Route::get('/customerservices-verifystatusbilletpayment/{transaction_id}', [CustomerServicesController::class,'verifystatusbilletpayment'])->name('backend.customerservices.verifystatusbilletpayment');
  Route::get('/customerservices-verifystatuspixpayment/{transaction_id}', [CustomerServicesController::class,'verifystatuspixpayment'])->name('backend.customerservices.verifystatuspixpayment');
  Route::get('/customerservices-teste', [CustomerServicesController::class,'teste'])->name('backend.customerservices.teste');

  //Customer Invoices
  Route::get('/invoices', [InvoicesController::class,'index'])->name('backend.invoices');
  Route::get('/invoices-create/{customer_id}', [InvoicesController::class,'create'])->name('backend.invoices.create');
  Route::get('/invoices-notification/{invoice_id}', [InvoicesController::class,'notification'])->name('backend.invoices.notification');
  Route::post('/invoices-store', [InvoicesController::class,'store'])->name('backend.invoices.store');
  Route::get('/invoices-edit/{customer_id}/{id}', [InvoicesController::class,'edit'])->name('backend.invoices.edit');
  Route::put('/invoices-update/{id}', [InvoicesController::class,'update'])->name('backend.invoices.update');
  Route::put('/invoices-confirm/{id}', [InvoicesController::class,'confirm'])->name('backend.invoices.confirm');
  Route::get('/invoices-details/{id}', [InvoicesController::class,'show'])->name('backend.invoices.show');
  Route::delete('/invoices-delete', [InvoicesController::class,'destroy'])->name('backend.invoices.delete');

   //Payables
   Route::get('/payables', [PayableController::class,'index'])->name('backend.payables');
   Route::get('/payables-create', [PayableController::class,'create'])->name('backend.payables.create');
   Route::get('/payables-notification/{invoice_id}', [PayableController::class,'notification'])->name('backend.payables.notification');
   Route::post('/payables-store', [PayableController::class,'store'])->name('backend.payables.store');
   Route::get('/payables-edit/{id}', [PayableController::class,'edit'])->name('backend.payables.edit');
   Route::put('/payables-update/{id}', [PayableController::class,'update'])->name('backend.payables.update');
   Route::put('/payables-confirm/{id}', [PayableController::class,'confirm'])->name('backend.payables.confirm');
   Route::get('/payables-details/{id}', [PayableController::class,'show'])->name('backend.payables.show');
   Route::delete('/payables-delete', [PayableController::class,'destroy'])->name('backend.payables.delete');

  //Services Actives
  Route::get('/servicescustomers', [ServicesActivesController::class,'index'])->name('backend.services.customers');

  //Services
  Route::get('/services', [ServiceController::class,'index'])->name('backend.services');
  Route::get('/services-create', [ServiceController::class,'create'])->name('backend.services.create');
  Route::post('/services-store', [ServiceController::class,'store'])->name('backend.services.store');
  Route::get('/services-edit/{id}', [ServiceController::class,'edit'])->name('backend.services.edit');
  Route::put('/services-update/{id}', [ServiceController::class,'update'])->name('backend.services.update');
  Route::get('/services-details/{id}', [ServiceController::class,'show'])->name('backend.services.show');
  Route::delete('/services-delete', [ServiceController::class,'destroy'])->name('backend.services.delete');

  //Services
  Route::get('/backups', [BackupController::class,'index'])->name('backend.backups');
  Route::get('/backups-create', [BackupController::class,'create'])->name('backend.backups.create');
  Route::post('/backups-store', [BackupController::class,'store'])->name('backend.backups.store');
  Route::get('/backups-edit/{id}', [BackupController::class,'edit'])->name('backend.backups.edit');
  Route::put('/backups-update/{id}', [BackupController::class,'update'])->name('backend.backups.update');
  Route::get('/backups-details/{id}', [BackupController::class,'show'])->name('backend.backups.show');
  Route::delete('/backups-delete', [BackupController::class,'destroy'])->name('backend.backups.delete');


  //Relatorios
  Route::get('/reports', [ReportsController::class,'index'])->name('backend.reports');

  //Configuração Geral
  Route::get('/configs', [ConfigController::class,'index'])->name('backend.configs');
  Route::put('/configs-update', [ConfigController::class,'update'])->name('backend.configs.update');

});
Route::auth();

