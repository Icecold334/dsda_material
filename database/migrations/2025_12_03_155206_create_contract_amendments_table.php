<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Support\Database\HasUuidPrimary;

return new class extends Migration {
    use HasUuidPrimary;

    public function up(): void
    {
        Schema::create('contract_amendments', function (Blueprint $table) {
            $this->uuid($table);

            $table->uuid('contract_id');
            $table->integer('amend_version')->default(1); // 1,2,3 dst
            $table->string('nomor')->nullable();
            $table->decimal('total', 18, 2)->default(0);
            $table->string('status')->default('draft');

            $table->foreign('contract_id')->references('id')->on('contracts')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contract_amendments');
    }
};
