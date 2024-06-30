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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->references('id')->on('categories');
            $table->foreignId('sub_category_id')->constrained()->references('id')->on('sub_categories');
            $table->foreignId('instructor_id')->constrained()->references('id')->on('users');
            $table->string('image')->nullable();
            $table->text('title')->nullable();
            $table->text('name')->nullable();
            $table->string('slug')->nullable();
            $table->longText('description')->nullable();
            $table->string('video_link')->nullable();
            $table->enum('course_level', ['Beginner', 'Intermediate', 'Advance'])->nullable();
            $table->string('duration')->nullable();
            $table->string('resources')->nullable();

            $table->enum('certificate', ['Yes', 'No'])->nullable();
            $table->decimal('selling_price', 8, 2)->nullable();
            $table->decimal('discount_price', 8, 2)->nullable();
            $table->text('prerequisites')->nullable();
            $table->enum('best_seller', ['1', '0'])->nullable();
            $table->enum('featured', ['1', '0'])->nullable();
            $table->enum('highest_rated', ['1', '0'])->nullable();
            $table->tinyInteger('status')->default(0)->comment('0 = Inactive, 1 = Active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
