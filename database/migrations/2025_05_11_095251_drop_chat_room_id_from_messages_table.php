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
        Schema::table('messages', function (Blueprint $table) {
            //
            $table->dropForeign(['chat_room_id']); // 必要なら
            $table->dropColumn('chat_room_id');
            $table->dropForeign(['user_id']); // 念のため
            $table->dropColumn('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            //
            $table->foreignId('chat_room_id')->constrained('chat_rooms')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
        });
    }
};
