<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Support\Database\HasUuidPrimary;

return new class extends Migration {
    use HasUuidPrimary;

    public function up(): void
    {
        Schema::create('stock_opnames', function (Blueprint $table) {
            $this->uuid($table);

            $table->uuid('sudin_id');
            $table->uuid('warehouse_id');
            $table->uuid('item_id');
            $table->uuid('user_id');        // yang melakukan opname

            $table->decimal('qty_system', 18, 2);     // yang tercatat di stocks
            $table->decimal('qty_real', 18, 2);       // hasil fisik
            $table->decimal('selisih', 18, 2);        // qty_real - qty_system

            $table->date('tanggal_opname');

            // FK
            $table->foreign('sudin_id')->references('id')->on('sudins');
            $table->foreign('warehouse_id')->references('id')->on('warehouses');
            $table->foreign('item_id')->references('id')->on('items');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_opnames');
    }
};
