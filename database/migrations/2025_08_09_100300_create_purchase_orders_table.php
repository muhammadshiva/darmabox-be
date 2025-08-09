<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained()->cascadeOnUpdate();
            $table->foreignId('created_by')->constrained('users')->cascadeOnUpdate();
            $table->string('source', 10)->default('cms');
            $table->enum('status', ['draft', 'sent', 'partially_received', 'received', 'closed', 'cancelled'])->default('draft');
            $table->string('po_number', 60)->unique();
            $table->date('expected_date')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('supplier_id');
            $table->index('status');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
