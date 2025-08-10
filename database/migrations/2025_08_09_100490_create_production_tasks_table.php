<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('production_tasks', function (Blueprint $table) {
            $table->id();
            // Link to production (one production per order), nullable to allow creating tasks directly for an order first
            $table->foreignId('production_id')->nullable()->constrained('productions')->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained('orders')->cascadeOnDelete();
            $table->string('title');
            $table->enum('priority', ['low', 'normal', 'high'])->default('normal');
            $table->text('description')->nullable();
            $table->date('start_date')->nullable();
            $table->date('due_date')->nullable();
            $table->unsignedInteger('estimated_hours')->nullable();
            $table->enum('status', ['not_started', 'waiting', 'in_progress', 'completed', 'blocked'])->default('not_started')->index();
            $table->unsignedTinyInteger('progress')->default(0); // 0-100
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['order_id', 'due_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('production_tasks');
    }
};
