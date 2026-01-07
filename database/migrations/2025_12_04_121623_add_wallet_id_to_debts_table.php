<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('debts', function (Blueprint $table) {
            // Menambahkan kolom wallet_id setelah user_id
            // nullable() agar data lama tidak error
            $table->foreignId('wallet_id')->nullable()->after('user_id')->constrained('wallets')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('debts', function (Blueprint $table) {
            $table->dropForeign(['wallet_id']);
            $table->dropColumn('wallet_id');
        });
    }
};
