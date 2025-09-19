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
            $table->foreignId('table_id')->nullable()->constrained();
            $table->foreignId('user_id')->constrained();
            $table->string('customer_name')->nullable();
            $table->enum('order_type', ['dine-in', 'takeout'])->default('dine-in');
            $table->enum('status', ['new', 'preparing', 'ready', 'served', 'completed', 'cancelled'])->default('new');
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->enum('payment_status', ['pending', 'paid', 'refunded'])->default('pending');
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
