<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Support\Database\HasUuidPrimary;

return new class extends Migration {
    use HasUuidPrimary;

    public function up(): void
    {
        Schema::create('rabs', function (Blueprint $table) {
            $this->uuid($table);

            $table->uuid('sudin_id');
            // $table->uuid('aktivitas_sub_kegiatan_id');  // hasil dari 4-level program/kegiatan/sub/AK

            $table->string('nomor')->nullable();   // nomor RAB
            $table->year('tahun');                // tahun anggaran
            $table->decimal('total', 18, 2)->default(0);
            $table->string('status')->default('draft'); // draft / approved / rejected

            $table->foreign('sudin_id')->references('id')->on('sudins')->cascadeOnDelete();
            // $table->foreign('aktivitas_sub_kegiatan_id')->references('id')->on('aktivitas_sub_kegiatans');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rabs');
    }
};
