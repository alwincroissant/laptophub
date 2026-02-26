<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('restock_transactions', function (Blueprint $table) {
            $table->increments('restock_id');
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('supplier_id');
            $table->unsignedInteger('managed_by');
            $table->unsignedInteger('quantity_added');
            $table->decimal('unit_cost', 10, 2);
            $table->dateTime('restocked_at')->useCurrent();
            $table->text('notes')->nullable();

            $table->foreign('product_id', 'fk_restock_product')
                ->references('product_id')
                ->on('products');

            $table->foreign('supplier_id', 'fk_restock_supplier')
                ->references('supplier_id')
                ->on('suppliers');

            $table->foreign('managed_by', 'fk_restock_manager')
                ->references('user_id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restock_transactions');
    }
};
