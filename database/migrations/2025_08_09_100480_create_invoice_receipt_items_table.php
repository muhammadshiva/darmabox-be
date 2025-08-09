<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('invoice_receipt_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ir_id')->constrained('invoice_receipts')->cascadeOnDelete();
            $table->foreignId('po_item_id')->constrained('purchase_order_items')->cascadeOnDelete();
            $table->foreignId('gr_item_id')->nullable()->constrained('goods_receipt_items')->nullOnDelete();
            $table->decimal('qty_invoiced', 14, 3)->default(0);
            $table->decimal('unit_price', 14, 2)->default(0);
            $table->string('uom', 30);
            $table->text('notes')->nullable();

            $table->index('ir_id');
            $table->index('po_item_id');
            $table->index('gr_item_id');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('invoice_receipt_items');
    }
};
