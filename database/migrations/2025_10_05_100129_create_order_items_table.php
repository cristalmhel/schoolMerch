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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            // Foreign key to the orders table
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            // Foreign key to the products table (for linking back to the product master)
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('set null');
            
            // Snapshot fields: These MUST be stored to preserve order integrity 
            // even if the product or price changes later.
            $table->string('product_name');
            $table->decimal('unit_price', 8, 2);
            $table->integer('quantity');
            $table->decimal('subtotal', 10, 2);
            $table->string('color')->nullable();
            $table->string('size')->nullable();
            $table->json('attributes')->nullable(); // Size, Color, etc. as JSON

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};