<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Support\Database\HasUuidPrimary;

return new class extends Migration {
    use HasUuidPrimary;

    public function up(): void
    {
        Schema::create('rab_amendment_items', function (Blueprint $table) {
            $this->uuid($table);

            $table->uuid('rab_amendment_id');
            $table->uuid('item_id');

            $table->decimal('qty', 18, 2);
            $table->decimal('price', 18, 2);
            $table->decimal('subtotal', 18, 2);

            $table->foreign('rab_amendment_id')
                ->references('id')->on('rab_amendments')->cascadeOnDelete();

            $table->foreign('item_id')->references('id')->on('items');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rab_amendment_items');
    }
};
