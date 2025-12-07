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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            // Link to the user who placed the order
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); 
            
            $table->string('order_number')->unique();

            $table->enum('order_type', ['pickup', 'delivery'])->default('delivery');
            $table->unsignedBigInteger('shipping_detail_id')->nullable();
            
            // Total amount paid by the customer
            $table->decimal('total_amount', 10, 2); 
            $table->decimal('shipping_cost', 8, 2)->default(0.00);
            $table->decimal('tax_amount', 8, 2)->default(0.00);

            // Order status tracking
            $table->enum('status', [
                'pending', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded'
            ])->default('pending');

            $table->string('payment_method')->nullable();
            $table->string('payment_transaction_id')->nullable()->unique();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};