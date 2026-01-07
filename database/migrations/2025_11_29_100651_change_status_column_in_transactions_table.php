<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Kita ubah kolom status menjadi VARCHAR(50) agar bisa menampung teks apa saja
        DB::statement("ALTER TABLE transactions MODIFY COLUMN status VARCHAR(50) DEFAULT 'completed'");
    }

    public function down(): void
    {
        // Kembalikan ke ENUM jika rollback (Opsional)
        // DB::statement("ALTER TABLE transactions MODIFY COLUMN status ENUM('pending', 'completed', 'failed') DEFAULT 'completed'");
    }
};
