<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('budgets', function (Blueprint $table) {
            // 1. Tambahkan kolom start_date jika belum ada
            if (!Schema::hasColumn('budgets', 'start_date')) {
                $table->date('start_date')->nullable()->after('amount');
            }

            // 2. Tambahkan kolom end_date jika belum ada
            if (!Schema::hasColumn('budgets', 'end_date')) {
                $table->date('end_date')->nullable()->after('start_date');
            }

            // 3. KITA HAPUS kode yang mengubah 'month' & 'year'
            // karena error mengatakan kolom ini tidak ada di database Anda.
            // $table->integer('month')->nullable()->change(); 
            // $table->integer('year')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('budgets', function (Blueprint $table) {
            if (Schema::hasColumn('budgets', 'start_date')) {
                $table->dropColumn('start_date');
            }
            if (Schema::hasColumn('budgets', 'end_date')) {
                $table->dropColumn('end_date');
            }
        });
    }
};
