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
    Schema::table('product_prices', function (Blueprint $table) {
        $table->integer('stok')->default(0)->after('harga');
    });
}

public function down()
{
    Schema::table('product_prices', function (Blueprint $table) {
        $table->dropColumn('stok');
    });
}

};
