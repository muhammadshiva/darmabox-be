<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('production_task_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_task_id')->constrained('production_tasks')->cascadeOnDelete();
            $table->foreignId('material_id')->nullable()->constrained('materials')->nullOnDelete();
            $table->string('material_name');
            $table->decimal('quantity', 12, 2)->default(0);
            $table->string('unit', 16)->default('pcs');
            $table->enum('readiness', ['not_ready', 'partial', 'ready', 'pending'])->default('not_ready')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('production_task_materials');
    }
};
