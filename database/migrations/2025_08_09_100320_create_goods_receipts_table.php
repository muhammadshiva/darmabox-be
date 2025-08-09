<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('goods_receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('po_id')->constrained('purchase_orders')->cascadeOnDelete();
            $table->foreignId('received_by')->constrained('users')->cascadeOnUpdate();
            $table->string('gr_number', 60)->unique();
            $table->string('delivery_note_no', 80)->nullable();
            $table->timestamp('received_at')->useCurrent();
            $table->text('notes')->nullable();

            $table->index('po_id');
            $table->index('received_at');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('goods_receipts');
    }
};
