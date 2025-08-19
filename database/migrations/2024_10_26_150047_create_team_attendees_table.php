<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team_attendees', function (Blueprint $table) {
            $table->id(); // Primary key: id
            $table->unsignedBigInteger('event_id'); // Foreign key for event
            $table->string('team_name'); // Name of the team
            $table->string('team_leader'); // Team leader's name
            $table->string('team_member_1')->nullable(); // Team members
            $table->string('team_member_2')->nullable();
            $table->string('team_member_3')->nullable();
            $table->string('team_member_4')->nullable();
            $table->string('team_member_5')->nullable();
            $table->timestamps(); // Created and updated timestamps
    
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('team_attendees');
    }
};
