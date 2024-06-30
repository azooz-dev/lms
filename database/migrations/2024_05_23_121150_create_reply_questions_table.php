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
        Schema::create('reply_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained()->references('id')->on('questions');
            $table->foreignId('course_id')->constrained()->references('id')->on('courses');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->longText('reply');
            $table->boolean('read_status')->default(false);
            $table->foreignId('instructor_id')->constrained()->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reply_questions');
    }
};
