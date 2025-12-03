<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Support\Database\HasUuidPrimary;

return new class extends Migration {
    use HasUuidPrimary;

    public function up(): void
    {
        Schema::create('delivery_items', function (Blueprint $table) {
            $this->uuid($table);

            $table->uuid('delivery_id');
            $table->uuid('item_id');

            $table->decimal('qty', 18, 2);      // qty yang dikirim
            $table->text('notes')->nullable();

            $table->foreign('delivery_id')->references('id')->on('deliveries')->cascadeOnDelete();
            $table->foreign('item_id')->references('id')->on('items');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_items');
    }
};
