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
    Schema::create('order_items', function (Blueprint $table) {
        $table->id();

        $table->unsignedBigInteger('order_id');
        $table->unsignedBigInteger('product_id');
        $table->unsignedBigInteger('product_price_id'); // relasi ke product_prices
        $table->integer('quantity')->default(1);

        $table->decimal('price', 15, 2); // harga final pada saat checkout

        $table->timestamps();

        $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        $table->foreign('product_price_id')->references('id')->on('product_prices')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
