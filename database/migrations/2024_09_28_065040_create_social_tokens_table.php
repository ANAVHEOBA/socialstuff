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
        Schema::create('social_tokens', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id');
        $table->string('provider'); // e.g., Facebook, Instagram, YouTube, etc.
        $table->string('token'); // Access token
        $table->string('refresh_token')->nullable(); // Refresh token
        $table->timestamp('expires_at')->nullable(); // Token expiration timestamp
        $table->timestamps();
    });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_tokens');
    }
};
