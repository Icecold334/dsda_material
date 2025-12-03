<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Support\Database\HasUuidPrimary;

return new class extends Migration {
    use HasUuidPrimary;

    public function up(): void
    {
        Schema::create('position_delegations', function (Blueprint $table) {
            $this->uuid($table);

            $table->uuid('user_id');           // siapa yang jadi PLT
            $table->uuid('position_id');       // jabatan yang diberikan
            $table->uuid('division_id')->nullable();
            $table->uuid('sudin_id');

            $table->date('start_date');
            $table->date('end_date');
            $table->string('reason')->nullable();
            $table->uuid('approved_by')->nullable();

            // FK
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('position_id')->references('id')->on('positions');
            $table->foreign('division_id')->references('id')->on('divisions');
            $table->foreign('sudin_id')->references('id')->on('sudins');
            $table->foreign('approved_by')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('position_delegations');
    }
};
