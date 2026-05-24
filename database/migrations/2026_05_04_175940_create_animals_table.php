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
        Schema::create('animals', function (Blueprint $table) {
                $table->id();
$table->string('tag_number')->unique()->nullable();
    $table->string('name')->nullable();
    $table->unsignedBigInteger('breed_id');
    $table->enum('sex', ['male', 'female']);
$table->decimal('weight', 8, 2);
    $table->date('birthdate')->nullable();
    $table->enum('status', ['active','sold','deceased','transferred'])->default('active');
    $table->timestamps();

$table->foreign("breed_id")
            ->references("id")
            ->on("breeds")
            ->restrictOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animals');
    }
};
