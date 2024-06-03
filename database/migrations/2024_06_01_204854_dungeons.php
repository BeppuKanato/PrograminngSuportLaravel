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
        Schema::create('dungeons', function(Blueprint $table) {
            $table->id()->comment('ダンジョンのユニークID');
            $table->string('name')->nullable(false)->comment("ダンジョン名");
            $table->text('description')->nullable(false)->comment('ダンジョンの説明');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dungeons');
    }
};
