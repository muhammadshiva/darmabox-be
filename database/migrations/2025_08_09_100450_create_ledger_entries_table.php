<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ledger_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained('accounts')->cascadeOnUpdate();
            $table->foreignId('journal_entry_id')->unique()->constrained('journal_entries')->cascadeOnDelete();
            $table->date('date');
            $table->decimal('balance_after', 16, 2);

            $table->index(['account_id', 'date']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('ledger_entries');
    }
};
