<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Support\Database\HasUuidPrimary;

return new class extends Migration {
    use HasUuidPrimary;

    public function up(): void
    {
        Schema::create('units', function (Blueprint $table) {
            $this->uuid($table);

            $table->uuid('sudin_id');
            $table->string('name');           // Unit Pengelola, Satgas, Posko
            $table->string('address')->nullable();

            $table->foreign('sudin_id')->references('id')->on('sudins')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
