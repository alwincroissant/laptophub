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
        Schema::create('order_items', function (Blueprint $table) {
            $table->increments('order_item_id');
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('product_id');
            $table->unsignedSmallInteger('quantity');
            $table->decimal('unit_price', 10, 2);

            $table->foreign('order_id', 'fk_order_items_order')
                ->references('order_id')
                ->on('orders')
                ->onDelete('cascade');

            $table->foreign('product_id', 'fk_order_items_product')
                ->references('product_id')
                ->on('products');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
