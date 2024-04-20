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
        $table->text('description');
        $table->text('extra_info')->nullable();
        $table->dateTime('start_time');
        $table->dateTime('end_time');
        $table->dateTime('register_start_time');
        $table->dateTime('register_end_time');
        $table->string('category');
        $table->string('location')->nullable();
        $table->string('url')->nullable();
        $table->boolean('online')->default(false);
        $table->unsignedInteger('maxUser');
        $table->boolean('paid')->default(false);
        $table->integer('price')->nullable();
        $table->unsignedInteger('earn_points')->nullable();
        $table->boolean('approved')->default(false);
        $table->boolean('approval')->default(false);
        $table->boolean('listed')->default(true);
        $table->unsignedInteger('status');
        $table->timestamps();
    
        // Indexes
        $table->index('user_id');
        $table->index('start_time');
        $table->index('end_time');
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
