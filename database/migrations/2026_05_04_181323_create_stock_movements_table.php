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
        Schema::create('stock_movements', function (Blueprint $table) {
      $table->id();
    $table->unsignedBigInteger('item_id');
    $table->unsignedBigInteger('user_id');
$table->unsignedBigInteger('purchase_history_id');
    $table->enum('type', ['stock_in', 'stock_out']);
    $table->integer('quantity');
    $table->string('lot_number')->nullable();
    $table->date('expiry_date')->nullable();
    $table->string('reason')->nullable();
    $table->timestamps();

$table->foreign("item_id")
            ->references("id")
            ->on("items")
            ->restrictOnDelete();

            $table->foreign("user_id")
            ->references("id")
            ->on("users")
            ->restrictOnDelete();

            $table->foreign("purchase_history_id")
            ->references("id")
            ->on("purchase_histories")
            ->restrictOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
