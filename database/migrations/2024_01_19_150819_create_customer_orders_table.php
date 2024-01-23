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
        Schema::create('customer_orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_number')->unique();
            $table->longText('order_token');
            $table->integer('customer_id');
            $table->string('customer');
            $table->string('product');
            $table->integer('returns')->nullable()->default(0);
            $table->string('day')->nullable();
            $table->integer('quantity');
            $table->decimal('price');
            $table->decimal('amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_orders');
    }
};
