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
        Schema::create('items', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('sku')->unique();
    $table->unsignedBigInteger('category_id');
    $table->unsignedBigInteger('unit_id');
    $table->text('description')->nullable();
    $table->integer('reorder_level')->default(0);
    $table->integer('current_stock')->default(0);
    $table->timestamps();

            $table->foreign("category_id")
            ->references("id")
            ->on("categories")
            ->restrictOnDelete();

            $table->foreign("unit_id")
            ->references("id")
            ->on("units_of_measure")
            ->restrictOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
