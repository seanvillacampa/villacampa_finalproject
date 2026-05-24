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
        Schema::create('animal_health_logs', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('animal_id');
    $table->foreignId('item_id')->nullable()->constrained()->nullOnDelete();
    $table->unsignedBigInteger('user_id');
    $table->enum('type', ['vaccination','deworming','medication','vet_visit','other']);
    $table->string('description');
    $table->string('medicine_used')->nullable();
    $table->string('dosage')->nullable();
    $table->decimal('dosage_quantity', 10, 2)->nullable();
    $table->string('administered_by')->nullable();
    $table->date('next_schedule_date')->nullable();
    $table->date('log_date');
    $table->timestamps();

    $table->foreign('animal_id')->references('id')->on('animals')->restrictOnDelete();
    $table->foreign('user_id')->references('id')->on('users')->restrictOnDelete();
});

    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animal_health_logs');
    }
};
