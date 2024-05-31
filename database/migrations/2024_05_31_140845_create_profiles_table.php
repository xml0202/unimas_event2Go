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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unique();
            $table->string('username');
            $table->string('universityId')->nullable();
            $table->string('fullname')->nullable();
            $table->string('email')->nullable();
            $table->string('altEmail')->nullable();
            $table->string('departmentCode')->nullable();
            $table->string('departmentName')->nullable();
            $table->string('salutation')->nullable();
            $table->string('phoneNo')->nullable();
            $table->string('officeCode')->nullable();
            $table->string('officeName')->nullable();
            $table->string('category')->nullable();
            $table->unsignedInteger('categoryCode')->nullable();
            $table->string('nationalId')->nullable();
            $table->boolean('staff')->nullable();
            $table->string('picture')->nullable();
            $table->text('extra')->nullable();
            $table->text('authorities')->nullable();
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
        Schema::dropIfExists('profiles');
    }
};
