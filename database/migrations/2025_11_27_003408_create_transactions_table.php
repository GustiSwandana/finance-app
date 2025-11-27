<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Hubungkan ke tabel wallets
            $table->foreignId('wallet_id')->constrained()->onDelete('cascade');
            
            // Hubungkan ke tabel categories (boleh kosong jika transfer)
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            
            $table->decimal('amount', 15, 2); // Jumlah uang
            $table->enum('type', ['income', 'expense', 'transfer']);
            $table->string('description')->nullable();
            $table->enum('status', ['pending', 'completed'])->default('completed');
            $table->date('date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};