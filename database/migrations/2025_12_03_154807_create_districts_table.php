<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Support\Database\HasUuidPrimary;

return new class extends Migration {
    use HasUuidPrimary;

    public function up(): void
    {
        // Table districts sudah tidak digunakan
        // Kecamatan sekarang disimpan di tabel divisions dengan type='district'
        // Migrasi ini di-comment untuk jaga-jaga jika ada data yang perlu dimigrate

        /*
        Schema::create('districts', function (Blueprint $table) {
            $this->uuid($table);

            $table->uuid('sudin_id');
            $table->string('name');

            $table->foreign('sudin_id')->references('id')->on('sudins')->cascadeOnDelete();
        });
        */
    }

    public function down(): void
    {
        // Schema::dropIfExists('districts');
    }
};
