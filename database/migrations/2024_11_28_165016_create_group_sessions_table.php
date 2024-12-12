<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('group_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained()->onDelete('cascade');
            $table->string('session_name');
            $table->timestamp('date_time');
            $table->integer('duration');
            $table->timestamp('end_date_time')->nullable();
            $table->integer('participation_limit')->nullable();
            $table->boolean('equipment_provided')->default(false);
            $table->string('location');
            $table->text('description')->nullable();
            $table->foreignId('leader_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down():void
    {
        Schema::dropIfExists('group_sessions');
    }
};
