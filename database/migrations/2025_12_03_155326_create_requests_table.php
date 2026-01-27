<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Support\Database\HasUuidPrimary;

return new class extends Migration {
    use HasUuidPrimary;

    public function up(): void
    {
        Schema::create('requests', function (Blueprint $table) {
            $this->uuid($table);

            $table->string('nomor')->nullable();     // nomor permintaan
            $table->uuid('rab_id')->nullable();      // relasi ke RAB
            $table->string('name');
            $table->uuid('sudin_id');               // data milik Sudin apa
            $table->uuid('warehouse_id')->nullable();
            $table->uuid('item_type_id')->nullable();  // tipe barang
            $table->uuid('district_id')->nullable();
            $table->uuid('subdistrict_id')->nullable();
            $table->text('address');
            $table->string('panjang')->nullable();
            $table->string('lebar')->nullable();
            $table->string('tinggi')->nullable();
            $table->string('nopol')->nullable();
            $table->uuid('unit_id')->nullable();    // unit kerja tujuan barang
            $table->uuid('user_id');                // pembuat permintaan

            // Diisi PENGURUS BARANG saat approval level operasional
            $table->uuid('driver_id')->nullable();
            $table->uuid('security_id')->nullable();

            $table->date('tanggal_permintaan')->nullable();
            $table->string('status')->default('draft'); // draft / pending / approved / rejected
            $table->text('notes')->nullable();

            // FK
            $table->foreign('rab_id')->references('id')->on('rabs')->nullOnDelete();
            $table->foreign('sudin_id')->references('id')->on('sudins');
            $table->foreign('warehouse_id')->references('id')->on('warehouses');
            $table->foreign('item_type_id')->references('id')->on('item_types');
            $table->foreign('district_id')->references('id')->on('divisions');  // kecamatan sekarang di divisions
            $table->foreign('subdistrict_id')->references('id')->on('subdistricts');
            $table->foreign('unit_id')->references('id')->on('units');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('driver_id')->references('id')->on('personnels');
            $table->foreign('security_id')->references('id')->on('personnels');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
