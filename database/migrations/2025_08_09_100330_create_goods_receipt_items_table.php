<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('goods_receipt_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gr_id')->constrained('goods_receipts')->cascadeOnDelete();
            $table->foreignId('po_item_id')->constrained('purchase_order_items')->cascadeOnUpdate();
            $table->decimal('qty_received', 14, 3);
            $table->string('uom', 30);
            $table->text('notes')->nullable();

            $table->index('gr_id');
            $table->index('po_item_id');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('goods_receipt_items');
    }
};
