<?php

// database/migrations/xxxx_create_products_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('post_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();

            $table->foreignId('category_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->string('name');
            $table->string('brand')->nullable();
            $table->integer('price');

            $table->string('image')->nullable();

            $table->enum('status', ['launching','active','soldout'])
                  ->default('launching');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};