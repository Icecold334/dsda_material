<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Support\Database\HasUuidPrimary;

return new class extends Migration {
    use HasUuidPrimary;

    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $this->uuid($table);

            // polymorphic
            $table->string('documentable_type');      // contoh: App\Models\Request
            $table->uuid('documentable_id');

            // kategori
            $table->string('category');               // kontrak_file, dispatch_signature_driver, opname_photo, etc.

            // file
            $table->string('file_path');
            $table->string('file_name')->nullable();
            $table->string('mime_type')->nullable();
            $table->decimal('size_kb', 18, 2)->nullable();

            // metadata
            $table->uuid('user_id')->nullable();      // siapa yang upload

            // FK
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
