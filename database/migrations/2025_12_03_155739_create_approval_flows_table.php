<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Support\Database\HasUuidPrimary;

return new class extends Migration {
    use HasUuidPrimary;

    public function up(): void
    {
        Schema::create('approval_flows', function (Blueprint $table) {
            $this->uuid($table);

            $table->uuid('sudin_id');
            $table->string('module');           // 'rab', 'contract', 'permintaan', 'delivery', etc.
            $table->integer('level');           // urutan 1 → 2 → 3
            $table->uuid('position_id');        // jabatan yang approve level ini
            $table->uuid('division_id')->nullable(); // jika NULL → lintas divisi

            $table->text('notes')->nullable();

            $table->foreign('sudin_id')->references('id')->on('sudins');
            $table->foreign('position_id')->references('id')->on('positions');
            $table->foreign('division_id')->references('id')->on('divisions');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('approval_flows');
    }
};
