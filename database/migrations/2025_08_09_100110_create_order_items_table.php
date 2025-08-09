<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->integer('quantity');
            $table->text('custom_note')->nullable();
            $table->decimal('line_price', 14, 2)->default(0);
            $table->decimal('discount_amount', 14, 2)->default(0);
            $table->decimal('discount_pct', 5, 2)->default(0);
            $table->decimal('price_override', 14, 2)->nullable();
            $table->decimal('final_line_total', 14, 2)->default(0);

            $table->index('order_id');
            $table->index('product_id');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
