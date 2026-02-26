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
        Schema::create('supplier_products', function (Blueprint $table) {
            $table->increments('supplier_product_id');
            $table->unsignedInteger('supplier_id');
            $table->unsignedInteger('product_id');

            $table->unique(['supplier_id', 'product_id'], 'uq_supplier_product');

            $table->foreign('supplier_id', 'fk_sup_prod_supplier')
                ->references('supplier_id')
                ->on('suppliers')
                ->onDelete('cascade');

            $table->foreign('product_id', 'fk_sup_prod_product')
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
        Schema::dropIfExists('supplier_products');
    }
};
