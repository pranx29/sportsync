<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('session_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('group_sessions')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unique(['session_id', 'user_id']);
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('session_user');
    }
    
};
