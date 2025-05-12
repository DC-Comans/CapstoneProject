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
    Schema::table('answers', function (Blueprint $table) {
        $table->unsignedBigInteger('quiz_id')->nullable()->after('question');

        // Optional: Add foreign key constraint (only if quizzes.id is the source)
        $table->foreign('quiz_id')->references('id')->on('quizzes')->onDelete('set null');
    });
    }

public function down(): void
    {
    Schema::table('answers', function (Blueprint $table) {
        $table->dropForeign(['quiz_id']);
        $table->dropColumn('quiz_id');
    });
    }

};
