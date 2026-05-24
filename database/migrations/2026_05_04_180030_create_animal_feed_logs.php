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
        Schema::create('animal_feed_logs', function (Blueprint $table) {
                $table->id();
    $table->unsignedBigInteger('animal_id');
    $table->unsignedBigInteger('item_id');
    $table->unsignedBigInteger('user_id');
    $table->decimal('quantity', 10, 2);
    $table->date('feed_date');
    $table->timestamps();


$table->foreign("animal_id")
            ->references("id")
            ->on("animals")
            ->restrictOnDelete();

            $table->foreign("item_id")
            ->references("id")
            ->on("items")
            ->restrictOnDelete();

            $table->foreign("user_id")
            ->references("id")
            ->on("users")
            ->restrictOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animal_feed_logs');
    }
};
