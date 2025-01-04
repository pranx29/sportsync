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
            $table->date('registrationDeadline')->nullable();
            $table->date('eventDate');
            $table->string('status')->default('Upcoming')->index();
            $table->timestamp('cancelled_at')->nullable();
            $table->time('startTime');
            $table->time('endTime');
            $table->string('eventName');
            $table->text('eventDescription');
            $table->string('sportType');
            $table->string('registrationType');
            $table->integer('maxParticipants');
            $table->integer('numberOfTeams')->nullable();
            $table->string('teamAssignment')->nullable();
            $table->string('venue')->nullable();
            $table->string('customLocationName')->nullable();
            $table->string('customLocationLink')->nullable();
            $table->enum('locationType', ['indoor', 'outdoor']);
            $table->text('rulesDescription');
            $table->boolean('notifyCreation')->default(false);
            $table->boolean('sendReminder')->default(false);
            $table->boolean('notifyAssignments')->default(false);
            $table->string('eventImage')->nullable();
            $table->string('rulesDocument')->nullable();
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
