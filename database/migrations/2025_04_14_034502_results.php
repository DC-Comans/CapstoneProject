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
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('TestNumber');
            $table->string('answer1')->nullable();
            $table->string('givenanswer1')->nullable();
            $table->string('answer2')->nullable();
            $table->string('givenanswer2')->nullable();
            $table->string('answer3')->nullable();
            $table->string('givenanswer3')->nullable();
            $table->string('answer4')->nullable();
            $table->string('givenanswer4')->nullable();
            $table->string('answer5')->nullable();
            $table->string('givenanswer5')->nullable();
            $table->string('answer6')->nullable();
            $table->string('givenanswer6')->nullable();
            $table->string('answer7')->nullable();
            $table->string('givenanswer7')->nullable();
            $table->string('answer8')->nullable();
            $table->string('givenanswer8')->nullable();
            $table->string('answer9')->nullable();
            $table->string('givenanswer9')->nullable();
            $table->string('answer10')->nullable();
            $table->string('givenanswer10')->nullable();
            $table->string('answer11')->nullable();
            $table->string('givenanswer11')->nullable();
            $table->string('answer12')->nullable();
            $table->string('givenanswer12')->nullable();
            $table->string('answer13')->nullable();
            $table->string('givenanswer13')->nullable();
            $table->string('answer14')->nullable();
            $table->string('givenanswer14')->nullable();
            $table->string('answer15')->nullable();
            $table->string('givenanswer15')->nullable();
            $table->string('answer16')->nullable();
            $table->string('givenanswer16')->nullable();
            $table->string('answer17')->nullable();
            $table->string('givenanswer17')->nullable();
            $table->string('answer18')->nullable();
            $table->string('givenanswer18')->nullable();
            $table->string('answer19')->nullable();
            $table->string('givenanswer19')->nullable();
            $table->string('answer20')->nullable();
            $table->string('givenanswer20')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
