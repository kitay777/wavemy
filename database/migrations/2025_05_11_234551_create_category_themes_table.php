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
        Schema::create('category_themes', function (Blueprint $table) {
            $table->id();
                $table->string('thumbnail_path'); // サムネイル画像
                $table->string('summary', 128);   // 概要
                $table->string('bonus', 256);     // 参加特典
                $table->string('fee', 128);       // 参加費
                $table->unsignedInteger('max_participants'); // 最大人数
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_themes');
    }
};
