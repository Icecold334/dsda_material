<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Support\Database\HasUuidPrimary;

return new class extends Migration {
    use HasUuidPrimary;

    public function up(): void
    {
        Schema::create('item_categories', function (Blueprint $table) {
            $this->uuid($table);

            $table->uuid('item_unit_id');
            $table->string('name');
            $table->string('slug')->unique();

            $table->foreign('item_unit_id')->references('id')->on('item_units')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_categories');
    }
};
