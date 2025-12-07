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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            // Link to the user who owns the cart (optional, for guest checkout support)
            // Assumes a 'users' table exists.
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            
            // For guest carts, we might use a session identifier
            $table->string('session_id')->nullable()->unique();
            
            // Status can track if the cart is active or merged into an order
            $table->enum('status', ['active', 'converted'])->default('active'); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};