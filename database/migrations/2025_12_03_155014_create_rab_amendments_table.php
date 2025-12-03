<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Support\Database\HasUuidPrimary;

return new class extends Migration {
    use HasUuidPrimary;

    public function up(): void
    {
        Schema::create('rab_amendments', function (Blueprint $table) {
            $this->uuid($table);

            $table->uuid('rab_id');
            $table->string('nomor')->nullable();   // nomor adendum RAB
            $table->integer('amend_version')->default(1);
            $table->decimal('total', 18, 2)->default(0);
            $table->string('status')->default('draft');

            $table->foreign('rab_id')->references('id')->on('rabs')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rab_amendments');
    }
};
