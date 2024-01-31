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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price');
            $table->integer('quantity')->default(0);
            $table->string('batch_number')->nullable()->default('N/A');
            $table->string('supplied_by')->default('N/A');
            $table->string('category')->default('N/A');
            $table->date('prod_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('image')->nullable()->default('');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
