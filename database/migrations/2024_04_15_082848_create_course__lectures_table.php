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
        Schema::create('course__lectures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->references('id')->on('courses');
            $table->foreignId('section_id')->constrained()->references('id')->on('course__sections');
            $table->string('lecture_title');
            $table->text('content')->nullable();
            $table->string('video')->nullable();
            $table->string('url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course__lectures');
    }
};
