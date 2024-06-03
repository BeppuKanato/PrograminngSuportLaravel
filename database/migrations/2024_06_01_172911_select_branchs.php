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
        Schema::create('select_branchs', function(Blueprint $table) {
            $table->id()->comment('選択肢のユニークID');
            $table->foreignId('questions_id')->constrained('questions')->onDelete('cascade')->nullable(false)->comment("問題のID");
            $table->string('content')->nullable(false)->comment('選択肢の内容');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('select_branchs');
    }
};
