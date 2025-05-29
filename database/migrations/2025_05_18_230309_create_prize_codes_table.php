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
        Schema::create('prize_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')
                ->constrained()
                ->onDelete('cascade');
            $table->string('code')->unique();
            $table->string('status')->default('unused');
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('viewed_at')->nullable();
            $table->timestamp('redeemed_at')->nullable();
            $table->string('session_id')->nullable();
            $table->timestamp('session_expires_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prize_codes');
    }
};
