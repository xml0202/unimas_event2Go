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
        Schema::create('feedback_ans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('event_id');
            $table->string('q1ans1')->nullable();
            $table->string('q1ans2')->nullable();
            $table->string('q1ans3')->nullable();
            $table->string('q1ans4')->nullable();
            $table->string('q2ans1')->nullable();
            $table->string('q2ans2')->nullable();
            $table->string('q2ans3')->nullable();
            $table->string('q2ans4')->nullable();
            $table->string('q2ans5')->nullable();
            $table->text('q3ans1')->nullable();
            $table->text('q4ans1')->nullable();
            $table->text('q4ans2')->nullable();
            $table->text('q4ans3')->nullable();
            $table->text('q5ans1')->nullable();
            $table->text('q6ans1')->nullable();
            $table->decimal('rating', 5, 2)->nullable();
            $table->timestamps();

            // Add foreign key constraints if needed
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('feedback_ans');
    }
};
