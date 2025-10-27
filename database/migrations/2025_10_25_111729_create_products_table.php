<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('store', function (Blueprint $table) {
            $table->id(); // يزيد تلقائي
            $table->string('product_name'); // نص
            $table->integer('quantity'); // رقم
            $table->string('location'); // نص
            $table->timestamps(); // تاريخ الإنشاء والتحديث
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store');
    }
};
