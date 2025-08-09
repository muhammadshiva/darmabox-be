<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('payables', function (Blueprint $table) {
            $table->foreignId('ir_id')->nullable()->after('gr_id')->constrained('invoice_receipts')->nullOnDelete();
            $table->index('ir_id');
        });
    }
    public function down(): void
    {
        Schema::table('payables', function (Blueprint $table) {
            $table->dropConstrainedForeignId('ir_id');
        });
    }
};
