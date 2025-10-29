<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('store_actions', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('store_id'); // رقم المنتج من جدول store
        $table->enum('action_type', ['add', 'withdraw']); // نوع الحركة
        $table->integer('quantity_changed'); // الكمية المضافة أو المسحوبة
        $table->timestamps();

        // الربط مع جدول store
        $table->foreign('store_id')->references('id')->on('store')->onDelete('cascade');
    });
}
};
