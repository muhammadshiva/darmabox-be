<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('production_task_teams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_task_id')->constrained('production_tasks')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('role')->nullable(); // e.g., carpenter, finisher
            $table->timestamps();
            $table->unique(['production_task_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('production_task_teams');
    }
};
