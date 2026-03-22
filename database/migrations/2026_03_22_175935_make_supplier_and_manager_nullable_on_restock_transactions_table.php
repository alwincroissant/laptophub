<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('restock_transactions', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('fk_restock_supplier');
                $table->dropForeign('fk_restock_manager');
            }
            
            $table->unsignedInteger('supplier_id')->nullable()->change();
            $table->unsignedInteger('managed_by')->nullable()->change();
            
            if (DB::getDriverName() !== 'sqlite') {
                $table->foreign('supplier_id', 'fk_restock_supplier')
                    ->references('supplier_id')
                    ->on('suppliers')
                    ->nullOnDelete();

                $table->foreign('managed_by', 'fk_restock_manager')
                    ->references('user_id')
                    ->on('users')
                    ->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restock_transactions', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('fk_restock_supplier');
                $table->dropForeign('fk_restock_manager');
            }
            
            $table->unsignedInteger('supplier_id')->nullable(false)->change();
            $table->unsignedInteger('managed_by')->nullable(false)->change();
            
            if (DB::getDriverName() !== 'sqlite') {
                $table->foreign('supplier_id', 'fk_restock_supplier')
                    ->references('supplier_id')
                    ->on('suppliers');

                $table->foreign('managed_by', 'fk_restock_manager')
                    ->references('user_id')
                    ->on('users');
            }
        });
    }
};
