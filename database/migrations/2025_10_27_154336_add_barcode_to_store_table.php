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
    Schema::table('store', function (Blueprint $table) {
        $table->string('barcode')->after('location');
    });
}

public function down()
{
    Schema::table('store', function (Blueprint $table) {
        $table->dropColumn('barcode');
    });
}

    };

