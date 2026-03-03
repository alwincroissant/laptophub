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
        Schema::create('products', function (Blueprint $table) {
            $table->increments('product_id');
            $table->unsignedSmallInteger('category_id');
            $table->unsignedSmallInteger('brand_id');
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->text('compatibility')->nullable();
            $table->string('image_url', 255)->nullable();
            $table->decimal('price', 10, 2);
            $table->unsignedInteger('stock_qty')->default(0);
            $table->unsignedInteger('low_stock_threshold')->default(5);
            $table->boolean('is_archived')->default(false);
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->softDeletes();

            $table->foreign('category_id', 'fk_products_category')
                ->references('category_id')
                ->on('categories');

            $table->foreign('brand_id', 'fk_products_brand')
                ->references('brand_id')
                ->on('brands');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
