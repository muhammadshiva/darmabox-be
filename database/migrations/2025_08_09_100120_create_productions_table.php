<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('productions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete()->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnUpdate();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('status', ['not_started', 'in_progress', 'done'])->default('not_started');
            $table->text('notes')->nullable();

            $table->index('status');
            $table->index(['start_date', 'end_date']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('productions');
    }
};
