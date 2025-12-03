<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Support\Database\HasUuidPrimary;

return new class extends Migration {
    use HasUuidPrimary;

    public function up(): void
    {
        Schema::create('stocks', function (Blueprint $table) {
            $this->uuid($table);

            $table->uuid('sudin_id');
            $table->uuid('warehouse_id');
            $table->uuid('item_id');
            $table->decimal('qty', 18, 2)->default(0);

            // FK
            $table->foreign('sudin_id')->references('id')->on('sudins');
            $table->foreign('warehouse_id')->references('id')->on('warehouses');
            $table->foreign('item_id')->references('id')->on('items');

            // UNIQUE: kombinasi gudang-item harus unik
            $table->unique(['warehouse_id', 'item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
