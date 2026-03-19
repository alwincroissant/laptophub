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
        Schema::table('restock_transactions', function (Blueprint $table) {
            $table->integer('quantity_added')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restock_transactions', function (Blueprint $table) {
            $table->unsignedInteger('quantity_added')->change();
        });
    }
};
