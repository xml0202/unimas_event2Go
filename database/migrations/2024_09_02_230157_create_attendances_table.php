<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();  // Auto-incrementing ID
            $table->foreignId('event_id')->constrained()->onDelete('cascade');  // Foreign key to events table
            $table->foreignId('user_id')->constrained()->onDelete('cascade');  // Foreign key to users table
            $table->dateTime('check_in_date')->nullable();  // Date and time when the user checked in
            $table->timestamps();  // Created at and updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendances');
    }
}
