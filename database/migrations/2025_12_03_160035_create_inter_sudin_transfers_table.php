<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Support\Database\HasUuidPrimary;

return new class extends Migration {
    use HasUuidPrimary;

    public function up(): void
    {
        Schema::create('inter_sudin_transfers', function (Blueprint $table) {
            $this->uuid($table);

            $table->uuid('sudin_pengirim_id');
            $table->uuid('sudin_penerima_id');
            $table->uuid('user_id');                   // yang membuat transfer

            $table->date('tanggal_transfer')->nullable();
            $table->string('status')->default('draft');  // draft/waiting/approved/rejected
            $table->text('notes')->nullable();

            // FK
            $table->foreign('sudin_pengirim_id')->references('id')->on('sudins');
            $table->foreign('sudin_penerima_id')->references('id')->on('sudins');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inter_sudin_transfers');
    }
};
