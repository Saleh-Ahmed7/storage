<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
{
    Schema::create('product_delete', function (Blueprint $table) {
        $table->id();

        // ربط مع جدول products بدون حذف مرتبط
        $table->unsignedBigInteger('product_id');
        $table->string('    ');

        // $table->foreign('product_id')->references('id')->on('products');
        $table->enum('action_type', ['deleted']);// نوع الحركة

        // $table->string('reason')->nullable(); // سبب الحذف (اختياري)
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('product_delete');
}

};
