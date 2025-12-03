<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Support\Database\HasUuidPrimary;

return new class extends Migration {
    use HasUuidPrimary;

    public function up(): void
    {
        Schema::create('request_items', function (Blueprint $table) {
            $this->uuid($table);

            $table->uuid('request_id');
            $table->uuid('item_id');

            $table->decimal('qty_request', 18, 2);     // qty yg diminta
            $table->decimal('qty_approved', 18, 2)->nullable();   // qty setelah disetujui
            $table->text('notes')->nullable();

            $table->foreign('request_id')->references('id')->on('requests')->cascadeOnDelete();
            $table->foreign('item_id')->references('id')->on('items');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('request_items');
    }
};
