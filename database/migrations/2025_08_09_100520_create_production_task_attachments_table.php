<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('production_task_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_task_id')->constrained('production_tasks')->cascadeOnDelete();
            $table->string('path');
            $table->string('label')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('production_task_attachments');
    }
};
