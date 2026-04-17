<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Hubungkan semua setting yang ada (atau buat default store)
        $storeId = DB::table('stores')->insertGetId([
            'name' => DB::table('settings')->where('key', 'store_name')->value('value') ?? 'Toko Pusat',
            'address' => DB::table('settings')->where('key', 'store_address')->value('value'),
            'phone' => DB::table('settings')->where('key', 'store_phone')->value('value'),
            'logo' => DB::table('settings')->where('key', 'store_logo')->value('value'),
            'default_tax' => DB::table('settings')->where('key', 'default_tax')->value('value') ?? 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $tables = ['users', 'categories', 'products', 'transactions', 'suppliers', 'purchases'];

        foreach ($tables as $tbl) {
            Schema::table($tbl, function (Blueprint $table) use ($storeId) {
                // For users, store_id can be nullable (owner might not have a specific store)
                $table->foreignId('store_id')->nullable()->default($storeId)->constrained('stores')->cascadeOnDelete();
            });
        }
    }

    public function down(): void
    {
        $tables = ['users', 'categories', 'products', 'transactions', 'suppliers', 'purchases'];

        foreach ($tables as $tbl) {
            Schema::table($tbl, function (Blueprint $table) {
                $table->dropForeign(['store_id']);
                $table->dropColumn('store_id');
            });
        }
    }
};
