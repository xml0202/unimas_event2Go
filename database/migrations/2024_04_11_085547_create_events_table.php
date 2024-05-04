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
        Schema::create('events', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained();
        $table->unsignedBigInteger('admin_id');
        $table->string('title');
        $table->string('attachment');
        $table->text('introduction');
        $table->text('organized_by');
        $table->text('in_collaboration');
        $table->text('program_objective');
        $table->text('program_impact');
        $table->text('invitation');
        $table->dateTime('start_datetime');
        $table->dateTime('end_datetime');
        $table->string('category');
        $table->string('location');
        $table->unsignedInteger('max_user');
        $table->integer('price')->nullable();
        $table->unsignedInteger('earn_points')->nullable();
        $table->boolean('approved')->default(false);
        $table->boolean('approval')->default(false);
        $table->unsignedInteger('status');
        $table->timestamps();
    
        // Indexes
        $table->index('user_id');
        $table->index('start_datetime');
        $table->index('end_datetime');
        // Add indexes to other columns as needed
    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
};
