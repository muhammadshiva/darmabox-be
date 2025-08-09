<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku', 80)->nullable()->unique();
            $table->string('name', 200);
            $table->enum('type', ['ready', 'custom'])->default('custom');
            $table->decimal('price', 14, 2)->default(0);
            $table->text('description')->nullable();
            $table->integer('stock')->default(0);
            $table->timestamp('created_at')->useCurrent();

            $table->index('type');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
