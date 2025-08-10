<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\GoodsReceiptController;
use App\Http\Controllers\API\CustomerController;
use App\Http\Controllers\API\SupplierController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\MaterialController;
use App\Http\Controllers\API\PurchaseOrderController;
use App\Http\Controllers\API\AccountController;
use App\Http\Controllers\API\JournalController;
use App\Http\Controllers\API\InvoiceReceiptController;
use App\Http\Controllers\API\ProductionTaskController;

Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // Masters
    Route::apiResource('customers', CustomerController::class);
    Route::apiResource('suppliers', SupplierController::class);
    Route::apiResource('products', ProductController::class);
    Route::apiResource('materials', MaterialController::class);

    // Sales
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
    Route::post('/orders/{order}/payments', [OrderController::class, 'recordPayment']);
    Route::post('/orders/{order}/finalize-ready', [OrderController::class, 'finalizeReady']);

    // Purchasing
    Route::apiResource('purchase-orders', PurchaseOrderController::class)->except(['edit', 'create']);
    Route::get('/goods-receipts', [GoodsReceiptController::class, 'index']);
    Route::post('/goods-receipts', [GoodsReceiptController::class, 'store']);
    Route::get('/invoice-receipts', [InvoiceReceiptController::class, 'index']);
    Route::post('/invoice-receipts', [InvoiceReceiptController::class, 'store']);

    // Finance
    Route::apiResource('accounts', AccountController::class)->except(['edit', 'create']);
    Route::apiResource('journals', JournalController::class)->only(['index', 'store', 'show']);

    // Production Planning
    Route::apiResource('production-tasks', ProductionTaskController::class)->except(['create', 'edit']);
    Route::post('/production-tasks/{productionTask}/attachments', [ProductionTaskController::class, 'uploadAttachment']);
    Route::delete('/production-tasks/{productionTask}/attachments/{attachmentId}', [ProductionTaskController::class, 'deleteAttachment']);
});
