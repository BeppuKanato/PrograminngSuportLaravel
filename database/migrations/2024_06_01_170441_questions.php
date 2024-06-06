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
        Schema::create('questions', function(Blueprint $table) {
            $table->id()->comment("問題のユニークID");
            $table->foreignId('language_id')->constrained('language_code')->onDelete('cascade')->nullable(false)->comment("言語コードのID");
            $table->foreignId('difficulty_id')->constrained('difficulty_code')->onDelete('cascade')->nullable(false)->comment("難易度コードのID");
            $table->foreignId('question_type_id')->constrained('question_type_code')->onDelete('cascade')->nullable(false)->comment("問題形式コードのID");
            $table->string('description')->nullable(false)->comment("問題の説明");
            $table->text('content')->nullable(false)->comment('問題の内容');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
