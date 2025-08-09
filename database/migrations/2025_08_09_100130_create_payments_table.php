<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 14, 2);
            $table->enum('payment_type', ['dp', 'final']);
            $table->enum('payment_method', ['cash', 'transfer', 'qris']);
            $table->timestamp('paid_at')->useCurrent();

            $table->index(['order_id', 'paid_at']);
            $table->index('payment_type');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
