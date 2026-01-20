<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Support\Database\HasUuidPrimary;

return new class extends Migration {
    use HasUuidPrimary;

    public function up(): void
    {
        Schema::create('subdistricts', function (Blueprint $table) {
            $this->uuid($table);

            $table->uuid('sudin_id');
            $table->uuid('division_id');  // kecamatan ada di divisions dengan type='district'
            // $table->uuid('district_id');  // old structure - kept for reference
            $table->string('name');

            $table->foreign('sudin_id')->references('id')->on('sudins')->cascadeOnDelete();
            $table->foreign('division_id')->references('id')->on('divisions')->cascadeOnDelete();
            // $table->foreign('district_id')->references('id')->on('districts')->cascadeOnDelete();  // old FK
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subdistricts');
    }
};
