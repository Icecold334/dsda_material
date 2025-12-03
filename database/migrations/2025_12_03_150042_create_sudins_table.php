<?php

use App\Support\Database\HasUuidPrimary;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    use HasUuidPrimary;

    public function up(): void
    {
        Schema::create('sudins', function (Blueprint $table) {
            $this->uuid($table); // id + softDeletes + timestamps

            $table->string('name');
            $table->string('short')->nullable();      // contoh: JAKSEL, KEP-SERIBU
            $table->string('address')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sudins');
    }
};
