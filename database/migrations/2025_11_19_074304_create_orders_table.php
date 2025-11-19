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
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id');           // id pelanggan
        $table->decimal('total_amount', 15, 2);          // total harga
        $table->string('status')->default('pending');    // pending, paid, cancelled
        $table->text('alamat')->nullable();              // alamat pengiriman
        $table->string('no_hp')->nullable();             // kontak pelanggan
        $table->timestamps();

        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
