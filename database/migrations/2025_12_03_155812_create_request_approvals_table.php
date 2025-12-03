<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Support\Database\HasUuidPrimary;

return new class extends Migration {
    use HasUuidPrimary;

    public function up(): void
    {
        Schema::create('request_approvals', function (Blueprint $table) {
            $this->uuid($table);

            // polymorphic relation ke dokumen
            $table->string('document_type');   // contoh: "App\Models\Request"
            $table->uuid('document_id');

            $table->uuid('sudin_id');
            $table->integer('level');          // level ke berapa
            $table->uuid('position_id');       // jabatan yang seharusnya approve
            $table->uuid('division_id')->nullable();

            $table->uuid('approved_by')->nullable(); // user actual
            $table->timestamp('approved_at')->nullable();
            $table->string('status')->default('pending');
            // pending / approved / rejected / skipped (kalau sama position di level sebelumnya)

            $table->text('notes')->nullable();

            // FK
            $table->foreign('sudin_id')->references('id')->on('sudins');
            $table->foreign('position_id')->references('id')->on('positions');
            $table->foreign('division_id')->references('id')->on('divisions');
            $table->foreign('approved_by')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('request_approvals');
    }
};
