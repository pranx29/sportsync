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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('eventName');
            $table->text('eventDescription');
            $table->string('sportType');
            $table->string('eventImage')->nullable();
            $table->string('registrationType');
            $table->integer('maxParticipants');
            $table->date('registrationDeadline')->nullable();
            $table->integer('numberOfTeams')->nullable();
            $table->string('teamAssignment')->nullable();
            $table->string('venue')->nullable();
            $table->string('customLocationName')->nullable();
            $table->string('customLocationLink')->nullable();
            $table->enum('locationType', ['indoor', 'outdoor']);
            $table->date('eventDate');
            $table->time('startTime');
            $table->time('endTime');
            $table->text('rulesDescription');
            $table->string('rulesDocument')->nullable();
            $table->boolean('notifyCreation')->default(false);
            $table->boolean('sendReminder')->default(false);
            $table->boolean('notifyAssignments')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
