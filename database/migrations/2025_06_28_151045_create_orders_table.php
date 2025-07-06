<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up(): void
        {
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->uuid('uuid')->unique();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->string('invoice_number')->unique();
                $table->integer('total_amount');
                $table->text('shipping_address');
                $table->string('payment_method');
                $table->string('payment_proof')->nullable();
                $table->string('shipping_receipt_number')->nullable();
                $table->string('status')->default('pending_payment');
                $table->timestamp('paid_at')->nullable();
                $table->timestamps();
            });
        }

        public function down(): void
        {
            Schema::dropIfExists('orders');
        }
    };
