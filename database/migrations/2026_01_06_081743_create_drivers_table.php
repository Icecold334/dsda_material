<?php

use App\Support\Database\HasUuidPrimary;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    use HasUuidPrimary;
    public function up(): void
    {
        Schema::create('drivers', function (Blueprint $table) {
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
        Schema::dropIfExists('drivers');
    }
};
