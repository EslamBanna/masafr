<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->integer('person_id');
            $table->enum('type',[0,1,2])->comment('notification 0 => to user, 1 => to masafr, 2 => to admin');
            $table->text('subject');
            $table->integer('target_code')->nullable()->comment('to know where he must move to like if value is 3 when he press on it he open his personal tap screen,,, and so on');
            $table->integer('related_trip')->nullable();
            $table->integer('related_request_service')->nullable();
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
        Schema::dropIfExists('notifications');
    }
}
