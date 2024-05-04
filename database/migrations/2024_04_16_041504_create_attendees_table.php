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
        Schema::create('attendees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id');
            $table->unsignedBigInteger('user_id');
            $table->tinyInteger('required_transport');
            $table->text('qrcode');
            $table->tinyInteger('attended');
            $table->tinyInteger('approved');
            $table->string('mobile_no', 255);
            $table->tinyInteger('status');
            $table->tinyInteger('gender');
            $table->string('addr_line_1', 255);
            $table->string('addr_line_2', 255);
            $table->text('postcode', 255);
            $table->text('city', 255);
            $table->text('state', 255);
            $table->text('country', 255);
            $table->timestamps();

            $table->foreign('event_id')->references('id')->on('events');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendees');
    }
};
