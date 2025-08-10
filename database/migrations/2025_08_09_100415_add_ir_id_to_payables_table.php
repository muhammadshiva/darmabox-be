<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('payables', function (Blueprint $table) {
            // Add column first without FK to avoid ordering issues when invoice_receipts table is not yet created
            if (!Schema::hasColumn('payables', 'ir_id')) {
                $table->unsignedBigInteger('ir_id')->nullable()->after('gr_id');
                $table->index('ir_id');
            }
        });
    }
    public function down(): void
    {
        Schema::table('payables', function (Blueprint $table) {
            if (Schema::hasColumn('payables', 'ir_id')) {
                $table->dropIndex(['ir_id']);
                $table->dropColumn('ir_id');
            }
        });
    }
};
