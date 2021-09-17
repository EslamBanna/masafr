<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTripDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trip_days', function (Blueprint $table) {
            $table->id();
            $table->integer('trip_id');
            $table->integer('trip_day')->comment('1 => Saturday, 2 => Sunday, 3 => Monday, 4 => Tuesday, 5 => Wednesday, 6 => Thursday, 7 => Friday');
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
        Schema::dropIfExists('trip_days');
    }
}
