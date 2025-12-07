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
        Schema::table('orders', function (Blueprint $table) {
            // Add the nullable foreign key for the pickup details table.
            // It is set to nullable because not all orders will be pickups (some will be delivery).
            $table->unsignedBigInteger('pickup_detail_id')->nullable()->after('order_type');
            
            // Define the foreign key constraint. This links the column to the 'id' of the 'pickup_details' table.
            // onDelete('set null') ensures that if a pickup detail record is somehow deleted, the order record remains 
            // but the pickup_detail_id is set to NULL.
            $table->foreign('pickup_detail_id')->references('id')->on('pickup_details')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropForeign(['pickup_detail_id']);
            
            // Then drop the column itself
            $table->dropColumn('pickup_detail_id');
        });
    }
};