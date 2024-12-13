<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up():void
    {
        Schema::create('group_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained()->onDelete('cascade');
            $table->string('session_name');
            $table->timestamp('date_time');
            $table->integer('duration');
            $table->integer('participation_limit')->nullable();
            $table->boolean('equipment_provided')->default(false);
            $table->string('location');
            $table->text(column: 'description')->nullable();
            $table->foreignId('leader_id')->constrained('users')->after('group_id')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down():void
    {
        Schema::dropIfExists('group_sessions');
    }
};
