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
        Schema::create('answers', function(Blueprint $table) {
            $table->id()->comment('答えのユニークID');
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade')->nullable(false)->comment("問題のID");
            $table->string('content')->nullable(false)->comment('正解の内容');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('answers');
    }
};
