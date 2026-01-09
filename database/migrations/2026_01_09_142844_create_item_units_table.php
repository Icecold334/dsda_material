<?php

use Illuminate\Support\Facades\Schema;
use App\Support\Database\HasUuidPrimary;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    use HasUuidPrimary;
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        Schema::create('item_units', function (Blueprint $table) {
            $this->uuid($table);
            $table->string('name');           // Batang, Kaleng
            $table->string('slug')->unique();

            $table->unique(['name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_units');
    }
};
