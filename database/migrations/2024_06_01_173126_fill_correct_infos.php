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
        Schema::create('fill_correct_infos', function(Blueprint $table) {
            $table->id()->comment('空欄の番号と回答の組み合わせのユニークID');
            $table->foreignId('questions_id')->constrained('questions')->onDelete('cascade')->nullable(false)->comment("問題のID");
            $table->foreignId('answer_id')->constrained('answers')->onDelete('cascade')->nullable(false)->comment("正解のID");
            $table->unsignedInteger('blank_number')->nullable(false)->comment('空欄の番号');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fill_corrent_infos');
    }
};
