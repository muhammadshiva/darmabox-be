<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cash_flows', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['cash_in', 'cash_out']);
            $table->string('source', 20)->nullable();
            $table->string('reference', 60)->nullable();
            $table->decimal('amount', 14, 2);
            $table->date('date');
            $table->text('note')->nullable();

            $table->index(['type', 'date']);
            $table->index('reference');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('cash_flows');
    }
};
