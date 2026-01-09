<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Support\Database\HasUuidPrimary;

return new class extends Migration {
    use HasUuidPrimary;

    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $this->uuid($table);

            $table->uuid('sudin_id');              // tiap sudin punya barang sendiri
            $table->uuid('item_category_id')->nullable();

            $table->string('spec');    // spesifikasi tambahan
            $table->string('slug');
            $table->boolean('active')->default(true);

            $table->foreign('sudin_id')->references('id')->on('sudins')->cascadeOnDelete();
            $table->foreign('item_category_id')->references('id')->on('item_categories')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
