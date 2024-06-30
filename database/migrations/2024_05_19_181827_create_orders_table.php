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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained()->references('id')->on('payments')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->references('id')->on('users');
            $table->foreignId('instructor_id')->constrained()->references('id')->on('users');
            $table->foreignId('course_id')->constrained();
            $table->string('course_title')->nullable();
            $table->string('course_image')->nullable();
            $table->string('course_price')->nullable();
            $table->timestamps();
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
