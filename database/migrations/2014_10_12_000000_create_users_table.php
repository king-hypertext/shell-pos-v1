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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('fullname')->default('super admin');
            $table->date('date_of_birth');
            $table->boolean('gender');
            $table->string('photo')->nullable()->default('');
            $table->boolean('admin')->default(0);
            $table->dateTime('login_at')->nullable();
            $table->dateTime('logout_at')->nullable();
            $table->integer('secret_code')->default(321456)->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
