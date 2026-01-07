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
            // Kolom untuk menyimpan total yang sudah dibayar/dicicil
            $table->decimal('paid_amount', 15, 2)->default(0)->after('amount');
        });
    }

    public function down()
    {
        Schema::table('debts', function (Blueprint $table) {
            $table->dropColumn('paid_amount');
        });
    }
};
