<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_services', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('type_of_trips')->comment('1 => delivery service, 2 => buy, 3 => Passenger delivery, 4 => public services');
            $table->integer('type_of_services')->comment('1 => light shipments, 2 => Miscellaneous shipments, 3 => plants, 4 => animals, 5 => Foods, 6 => devices');
            $table->string('from_place')->nullable();
            $table->string('from_longitude')->nullable();
            $table->string('from_latitude')->nullable();
            $table->string('to_place')->nullable();
            $table->string('to_longitude')->nullable();
            $table->string('to_latitude')->nullable();
            $table->date('max_day')->nullable();
            $table->boolean('delivery_to')->nullable()->comment('0 to home 1 on the road');
            $table->string('photo')->nullable();
            $table->string('description')->nullable();
            $table->boolean('only_women')->default(0)->comment('0 any one, 1 only womens');
            $table->boolean('have_insurance')->nullable()->comment('0 does not have insurance, 1 Have insurance');
            $table->string('website_service')->nullable();
            $table->integer('number_of_passengers')->nullable();
            $table->enum('type_of_car',[1,2])->nullable()->comment('1 => big car transport, 2 => family car');
            $table->boolean('on_progress')->default(0)->comment('0 no one(masafr) take it , 1 some one take it and it is in progress');
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
        Schema::dropIfExists('request_services');
    }
}
