<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->foreignId('sport_id')->constrained('sports');
            $table->string('image')->nullable();
            $table->enum('registration_type', ['individual', 'team']);
            $table->integer('max_participants');
            $table->date('registration_deadline')->nullable();
            $table->integer('number_of_teams')->nullable();
            $table->string('team_assignment')->nullable();
            $table->foreignId('venue_id')->nullable()->constrained('venues')->nullOnDelete();
            $table->string('custom_location_name')->nullable();
            $table->string('custom_location_link')->nullable();
            $table->enum('location_type', ['indoor', 'outdoor']);
            $table->date('event_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->text('rules_description');
            $table->string('rules_document')->nullable();
            $table->boolean('notify_creation')->default(false);
            $table->boolean('send_reminder')->default(false);
            $table->boolean('notify_assignments')->default(false);
            $table->enum('status', ['upcoming', 'completed', 'canceled'])->default('upcoming');
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
