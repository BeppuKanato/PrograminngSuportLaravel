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
        Schema::create('dungeon_questions', function(Blueprint $table) {
            $table->id()->comment('ダンジョンに収録する問題のID');
            $table->foreignId('dungeon_id')->constrained('dungeons')->onDelete('cascade')->nullable(false)->comment("ダンジョンのID");
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade')->nullable(false)->comment("問題のID");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dungeon_questions');
    }
};
