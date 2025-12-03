<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Support\Database\HasUuidPrimary;

return new class extends Migration {
    use HasUuidPrimary;

    public function up(): void
    {
        Schema::create('stock_transactions', function (Blueprint $table) {
            $this->uuid($table);

            // sumber mutasi
            $table->uuid('sudin_id');
            $table->uuid('warehouse_id');
            $table->uuid('item_id');

            // ledger info
            $table->enum('type', [
                'IN',
                'OUT',
                'ADJUST',
                'OPNAME',
                'DELIVERY',
                'REQUEST_OUT',
                'IMPORT'
            ]);

            $table->decimal('qty', 18, 2);      // bisa + atau -
            $table->decimal('before_qty', 18, 2)->nullable();
            $table->decimal('after_qty', 18, 2)->nullable();

            // referensi polymorphic ke dokumen
            $table->string('ref_type')->nullable();   // contoh: ContractReceipt, Request, Delivery
            $table->uuid('ref_id')->nullable();

            $table->uuid('user_id')->nullable();      // siapa yang input

            // FK
            $table->foreign('sudin_id')->references('id')->on('sudins');
            $table->foreign('warehouse_id')->references('id')->on('warehouses');
            $table->foreign('item_id')->references('id')->on('items');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_transactions');
    }
};
