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
        Schema::create('order_status_logs', function (Blueprint $table) {
            $table->increments('log_id');
            $table->unsignedInteger('order_id');
            $table->unsignedTinyInteger('status_id');
            $table->unsignedInteger('changed_by');
            $table->dateTime('changed_at')->useCurrent();
            $table->text('note')->nullable();

            $table->foreign('order_id', 'fk_order_logs_order')
                ->references('order_id')
                ->on('orders')
                ->onDelete('cascade');

            $table->foreign('status_id', 'fk_order_logs_status')
                ->references('status_id')
                ->on('order_statuses');

            $table->foreign('changed_by', 'fk_order_logs_user')
                ->references('user_id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_status_logs');
    }
};
