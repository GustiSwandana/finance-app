<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('users') || ! Schema::hasColumns('users', ['role', 'is_active'])) {
            return;
        }

        DB::table('users')
            ->where('is_active', false)
            ->update(['is_active' => true]);

        if (DB::table('users')->where('role', 'admin')->exists()) {
            return;
        }

        $firstUserId = DB::table('users')->orderBy('id')->value('id');

        if ($firstUserId) {
            DB::table('users')
                ->where('id', $firstUserId)
                ->update([
                    'role' => 'admin',
                    'is_active' => true,
                ]);
        }
    }

    public function down(): void
    {
        //
    }
};
