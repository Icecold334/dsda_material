<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Support\Database\HasUuidPrimary;

return new class extends Migration {
    use HasUuidPrimary;

    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $this->uuid($table);

            $table->uuid('user_id')->nullable();
            $table->string('action');                 // create, update, approve, reject, login, upload_file, etc.
            $table->string('module')->nullable();     // request, contract, rab, delivery, stock, etc.
            $table->text('description')->nullable();

            // optional polymorphic
            $table->string('reference_type')->nullable();
            $table->uuid('reference_id')->nullable();

            // FK
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
