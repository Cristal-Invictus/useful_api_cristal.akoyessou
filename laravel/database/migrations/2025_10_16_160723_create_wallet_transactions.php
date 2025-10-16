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
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender_id')->nullable();
            $table->unsignedBigInteger('receiver_id')->nullable();
            $table->decimal('amount', 20, 2);
            $table->string('status'); // success | failed
            $table->timestamps();

            $table->foreign('sender_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('receiver_id')->references('id')->on('users')->nullOnDelete();
            $table->index(['sender_id','receiver_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
