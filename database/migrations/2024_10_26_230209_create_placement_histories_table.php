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
        Schema::create('placement_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id'); // Reference to the events table
            $table->unsignedBigInteger('participant_id'); // This can be either user_id or team_id
            $table->boolean('is_team'); // true if participant_id is a team, false if user
            $table->unsignedBigInteger('placement_id'); // Reference to the point_setups table
            $table->integer('points_awarded'); // Points awarded for the placement
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('placement_histories');
    }
};
