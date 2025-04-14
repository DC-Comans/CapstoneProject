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
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('question1')->nullable();
            $table->string('answer1')->nullable();
            $table->string('question2')->nullable();
            $table->string('answer2')->nullable();
            $table->string('question3')->nullable();
            $table->string('answer3')->nullable();
            $table->string('question4')->nullable();
            $table->string('answer4')->nullable();
            $table->string('question5')->nullable();
            $table->string('answer5')->nullable();
            $table->string('question6')->nullable();
            $table->string('answer6')->nullable();
            $table->string('question7')->nullable();
            $table->string('answer7')->nullable();
            $table->string('question8')->nullable();
            $table->string('answer8')->nullable();
            $table->string('question9')->nullable();
            $table->string('answer9')->nullable();
            $table->string('question10')->nullable();
            $table->string('answer10')->nullable();
            $table->string('question11')->nullable();
            $table->string('answer11')->nullable();
            $table->string('question12')->nullable();
            $table->string('answer12')->nullable();
            $table->string('question13')->nullable();
            $table->string('answer13')->nullable();
            $table->string('question14')->nullable();
            $table->string('answer14')->nullable();
            $table->string('question15')->nullable();
            $table->string('answer15')->nullable();
            $table->string('question16')->nullable();
            $table->string('answer16')->nullable();
            $table->string('question17')->nullable();
            $table->string('answer17')->nullable();
            $table->string('question18')->nullable();
            $table->string('answer18')->nullable();
            $table->string('question19')->nullable();
            $table->string('answer19')->nullable();
            $table->string('question20')->nullable();
            $table->string('answer20')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};
