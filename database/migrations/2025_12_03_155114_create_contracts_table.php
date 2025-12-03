<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Support\Database\HasUuidPrimary;

return new class extends Migration {
    use HasUuidPrimary;

    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $this->uuid($table);

            $table->uuid('sudin_id');
            $table->uuid('vendor_id');

            $table->string('nomor')->nullable();     // nomor kontrak
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();

            $table->decimal('nilai_kontrak', 18, 2)->default(0);
            $table->string('status')->default('draft');  // draft / approved

            $table->foreign('sudin_id')->references('id')->on('sudins');
            $table->foreign('vendor_id')->references('id')->on('vendors');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
