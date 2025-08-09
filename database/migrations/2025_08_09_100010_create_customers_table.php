<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('phone', 50)->nullable();
            $table->text('address')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('name');
            $table->index('phone');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
