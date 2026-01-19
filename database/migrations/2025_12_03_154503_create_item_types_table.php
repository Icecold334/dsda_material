<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Support\Database\HasUuidPrimary;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    use HasUuidPrimary;
    public function up(): void
    {
        Schema::create('item_types', function (Blueprint $table) {
            $this->uuid($table);
            $table->string('name');
            $table->string('slug')->unique();
            $table->boolean('active')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_types');
    }
};
