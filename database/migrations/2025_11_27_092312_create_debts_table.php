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
        Schema::create('debts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Nama orangnya (Pemberi pinjaman / Peminjam)
            $table->enum('type', ['payable', 'receivable']); // payable = Saya Utang, receivable = Orang Utang
            $table->decimal('amount', 15, 2); // Jumlah utang
            $table->date('due_date')->nullable(); // Jatuh tempo
            $table->string('description')->nullable();
            $table->boolean('is_paid')->default(false); // Status lunas/belum
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('debts');
    }
};
