<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('po_id')->constrained('purchase_orders')->cascadeOnDelete();
            $table->string('item_type', 10);
            $table->unsignedBigInteger('item_id');
            $table->decimal('qty_ordered', 14, 3);
            $table->decimal('unit_price', 14, 2)->default(0);
            $table->string('uom', 30);
            $table->text('notes')->nullable();

            $table->index('po_id');
            $table->index(['item_type', 'item_id']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_items');
    }
};
