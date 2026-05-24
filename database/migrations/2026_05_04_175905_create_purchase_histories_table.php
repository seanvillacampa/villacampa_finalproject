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
        Schema::create('purchase_histories', function (Blueprint $table) {
                $table->id();
    $table->unsignedBigInteger('supplier_id');
    $table->unsignedBigInteger('user_id');
    $table->string('reference_number')->nullable();
    $table->date('purchase_date');
    $table->decimal('total_amount', 12, 2)->default(0);
    $table->enum('status', ['pending', 'received', 'cancelled'])->default('pending');
    $table->timestamps();

$table->foreign("supplier_id")
            ->references("id")
            ->on("suppliers")
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
        Schema::dropIfExists('purchase_histories');
    }
};
