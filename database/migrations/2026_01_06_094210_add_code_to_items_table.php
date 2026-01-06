<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1️⃣ Tambah kolom dulu (nullable, TANPA unique)
        Schema::table('items', function (Blueprint $table) {
            $table->string('code')->nullable()->after('item_category_id');
        });

        // 2️⃣ Isi data code
        DB::table('items')->update([
            'code' => DB::raw("CONCAT('ITEM-', LEFT(id, 8))")
        ]);

        // 3️⃣ Baru tambahkan UNIQUE constraint
        Schema::table('items', function (Blueprint $table) {
            $table->unique('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropUnique(['code']);
            $table->dropColumn('code');
        });
    }
};
