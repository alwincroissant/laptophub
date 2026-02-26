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
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('order_id');
            $table->unsignedInteger('user_id');
            $table->unsignedTinyInteger('payment_method_id');
            $table->unsignedTinyInteger('status_id');
            $table->text('shipping_address');
            $table->dateTime('placed_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('user_id', 'fk_orders_user')
                ->references('user_id')
                ->on('users');

            $table->foreign('payment_method_id', 'fk_orders_payment_method')
                ->references('payment_method_id')
                ->on('payment_methods');

            $table->foreign('status_id', 'fk_orders_status')
                ->references('status_id')
                ->on('order_statuses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
