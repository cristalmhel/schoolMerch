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
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            // Foreign key to the carts table
            $table->foreignId('cart_id')->constrained()->onDelete('cascade');
            // Foreign key to the products table (assumed to exist)
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            
            $table->integer('quantity')->default(1);
            
            // Snapshot of the price at the time of adding to the cart 
            // (important if prices change later)
            $table->decimal('price', 8, 2);
            $table->string('color')->nullable();
            $table->string('size')->nullable();
            
            // To handle variations (e.g., Size: L, Color: Blue)
            $table->json('attributes')->nullable(); 

            $table->timestamps();

            // Prevent adding the exact same product/attributes combo multiple times in one cart
            $table->unique(['cart_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};