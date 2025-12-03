<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Support\Database\HasUuidPrimary;

return new class extends Migration {
    use HasUuidPrimary;

    public function up(): void
    {
        Schema::create('contract_items', function (Blueprint $table) {
            $this->uuid($table);

            $table->uuid('contract_id');
            $table->uuid('item_id');

            $table->decimal('qty', 18, 2);
            $table->decimal('price', 18, 2);
            $table->decimal('subtotal', 18, 2);

            $table->foreign('contract_id')->references('id')->on('contracts')->cascadeOnDelete();
            $table->foreign('item_id')->references('id')->on('items');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contract_items');
    }
};
