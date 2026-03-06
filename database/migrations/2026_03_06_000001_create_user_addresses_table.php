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
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->increments('address_id');
            $table->unsignedInteger('user_id');
            $table->string('label', 50)->nullable();
            $table->string('recipient_name', 100);
            $table->string('phone', 20);
            $table->string('region', 100);
            $table->string('city', 100);
            $table->string('postal_code', 10);
            $table->string('street_address', 255);
            $table->boolean('is_default')->default(false);
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('user_id', 'fk_user_addresses_user')
                ->references('user_id')
                ->on('users')
                ->onDelete('cascade');

            $table->index(['user_id', 'is_default'], 'idx_user_addresses_user_default');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_addresses');
    }
};
