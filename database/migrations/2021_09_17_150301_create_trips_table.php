<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTripsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->integer('masafr_id');
            // Hint: in every trip type there are it's own data take care...
            $table->integer('type_of_trips')->comment('1 => trip with specific time, 2 => weekly trip 3 => travel when there are order 4 => city services');
            $table->integer('type_of_services')->comment('1 => light shipments, 2 => Miscellaneous shipments, 3 => plants and animals, 4 => Food and gifts, 5 => city services, 6 => Passengers, 7 => all');
            $table->boolean('only_women')->default(0)->comment('0 any one, 1 only womens');
            $table->string('from_place')->nullable();
            $table->string('from_longitude')->nullable();
            $table->string('from_latitude')->nullable();
            $table->string('to_place')->nullable();
            $table->string('to_longitude')->nullable();
            $table->string('to_latitude')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('description')->nullable();
            $table->boolean('active')->default(1)->comment('0 not active , 1 active');

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
        Schema::dropIfExists('trips');
    }
}
