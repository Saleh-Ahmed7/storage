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
        Schema::table('store_actions', function (Blueprint $table) {
            $table->enum('action_type', ['add', 'withdraw', 'new_product'])->change(); // نوع الحركة
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('store_actions', function (Blueprint $table) {
            $table->dropColumn('action_type');
        });
    }
};
