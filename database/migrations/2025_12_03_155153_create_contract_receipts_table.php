<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Support\Database\HasUuidPrimary;

return new class extends Migration {
    use HasUuidPrimary;

    public function up(): void
    {
        Schema::create('contract_receipts', function (Blueprint $table) {
            $this->uuid($table);

            $table->uuid('contract_id');
            $table->uuid('contract_item_id');
            $table->uuid('warehouse_id');

            $table->decimal('qty', 18, 2);          // jumlah di terima
            $table->date('tanggal_terima')->nullable();

            $table->foreign('contract_id')->references('id')->on('contracts')->cascadeOnDelete();
            $table->foreign('contract_item_id')->references('id')->on('contract_items')->cascadeOnDelete();
            $table->foreign('warehouse_id')->references('id')->on('warehouses');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contract_receipts');
    }
};
