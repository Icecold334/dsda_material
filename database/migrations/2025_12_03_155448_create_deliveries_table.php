<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Support\Database\HasUuidPrimary;

return new class extends Migration {
    use HasUuidPrimary;

    public function up(): void
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $this->uuid($table);

            $table->uuid('sudin_id');
            $table->uuid('unit_id')->nullable();
            $table->uuid('user_id');             // pembuat dokumen

            // Diisi oleh PENGURUS BARANG saat proses operasional
            $table->uuid('driver_id')->nullable();
            $table->uuid('security_id')->nullable();

            $table->date('tanggal_pengiriman')->nullable();
            $table->string('status')->default('draft');   // draft / waiting / approved / rejected
            $table->text('notes')->nullable();

            // FK
            $table->foreign('sudin_id')->references('id')->on('sudins');
            $table->foreign('unit_id')->references('id')->on('units');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('driver_id')->references('id')->on('personnels');
            $table->foreign('security_id')->references('id')->on('personnels');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
