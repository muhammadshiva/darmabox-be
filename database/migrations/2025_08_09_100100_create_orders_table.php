<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnUpdate();
            $table->foreignId('user_id')->constrained('users')->cascadeOnUpdate();
            $table->enum('status', ['draft', 'dp', 'in_production', 'done', 'paid'])->default('draft');
            $table->decimal('dp_amount', 14, 2)->default(0);
            $table->decimal('total_amount', 14, 2)->default(0);
            $table->string('invoice_code', 60)->unique();
            $table->timestamps();

            $table->index('status');
            $table->index('created_at');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
