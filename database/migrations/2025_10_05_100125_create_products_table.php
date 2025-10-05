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
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // Basic Info
            $table->string('product_name');
            $table->string('product_id')->nullable()->unique(); // e.g. PRD-001
            $table->string('sku')->unique(); // e.g. SKU-TSH-BLU-001
            $table->text('description')->nullable();

            // Pricing & Inventory
            $table->decimal('price', 10, 2)->default(0);
            $table->integer('stock_quantity')->default(0);
            $table->string('category')->nullable();
            $table->string('department')->nullable();

            // Attributes
            $table->string('color')->nullable();
            $table->string('available_sizes')->nullable(); // store as CSV: "XS,S,M,L,XL,XXL"
            $table->enum('status', ['Active', 'Inactive', 'Low', 'Out'])->default('Active');

            // Additional Settings
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_limited')->default(false);
            $table->boolean('available_online')->default(true);

            // Image
            $table->string('image_path')->nullable(); // store path like: uploads/products/image.jpg

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
