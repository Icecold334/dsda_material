<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Support\Database\HasUuidPrimary;

return new class extends Migration {
    use HasUuidPrimary;

    public function up(): void
    {
        Schema::create('positions', function (Blueprint $table) {
            $this->uuid($table);

            $table->string('name'); // contoh: Kepala Seksi, Kasubag, Staff, PPK
            $table->string('slug');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};
