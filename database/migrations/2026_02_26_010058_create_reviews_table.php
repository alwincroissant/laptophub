<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->increments('review_id');
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('order_item_id')->unique('uq_review_order_item');
            $table->unsignedTinyInteger('rating');
            $table->string('title', 150)->nullable();
            $table->text('body')->nullable();
            $table->boolean('is_visible')->default(true);
            $table->dateTime('created_at')->useCurrent();

            $table->foreign('product_id', 'fk_reviews_product')
                ->references('product_id')
                ->on('products');

            $table->foreign('user_id', 'fk_reviews_user')
                ->references('user_id')
                ->on('users');

            $table->foreign('order_item_id', 'fk_reviews_order_item')
                ->references('order_item_id')
                ->on('order_items');
        });

        DB::statement('ALTER TABLE `reviews` ADD CONSTRAINT `chk_rating` CHECK (`rating` BETWEEN 1 AND 5)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
