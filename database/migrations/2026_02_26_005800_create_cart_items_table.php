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
        Schema::create('cart_items', function (Blueprint $table) {
            $table->increments('cart_item_id');
            $table->unsignedInteger('cart_id');
            $table->unsignedInteger('product_id');
            $table->unsignedSmallInteger('quantity')->default(1);

            $table->unique(['cart_id', 'product_id'], 'uq_cart_product');

            $table->foreign('cart_id', 'fk_cart_items_cart')
                ->references('cart_id')
                ->on('carts')
                ->onDelete('cascade');

            $table->foreign('product_id', 'fk_cart_items_product')
                ->references('product_id')
                ->on('products')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
