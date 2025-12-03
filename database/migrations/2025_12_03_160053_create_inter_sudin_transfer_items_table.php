<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Support\Database\HasUuidPrimary;

return new class extends Migration {
    use HasUuidPrimary;

    public function up(): void
    {
        Schema::create('inter_sudin_transfer_items', function (Blueprint $table) {
            $this->uuid($table);

            $table->uuid('inter_sudin_transfer_id');
            $table->uuid('item_id');
            $table->decimal('qty', 18, 2);
            $table->text('notes')->nullable();

            // FK
            $table->foreign('inter_sudin_transfer_id')
                ->references('id')->on('inter_sudin_transfers')
                ->cascadeOnDelete();

            $table->foreign('item_id')->references('id')->on('items');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inter_sudin_transfer_items');
    }
};
