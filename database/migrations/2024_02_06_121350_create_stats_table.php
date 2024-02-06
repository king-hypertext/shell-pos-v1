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
        Schema::create('stats', function (Blueprint $table) {
            $table->id();
            $table->string('product');
            $table->integer('product_id');
            $table->integer('qty_received')->default(0);
            $table->string('from')->nullable()->default('N/A');
            $table->string('supplied')->nullable()->default('N/A');
            $table->string('to')->nullable()->default('N/A');
            $table->integer('before_qty')->default(0);
            $table->integer('after_qty')->default(0);
            $table->integer('qty')->default(0);
            $table->dateTime('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stats');
    }
};
