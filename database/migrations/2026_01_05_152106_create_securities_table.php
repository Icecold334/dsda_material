<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Support\Database\HasUuidPrimary;

return new class extends Migration {
    use HasUuidPrimary;
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('securities', function (Blueprint $table) {
            $this->uuid($table);
            $table->string('name');

            // DSDA custom fields
            $table->uuid('sudin_id')->nullable();

            // Relations
            $table->foreign('sudin_id')->references('id')->on('sudins')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('securities');
    }
};
