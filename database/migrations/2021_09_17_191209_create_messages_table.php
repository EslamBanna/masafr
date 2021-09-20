<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->integer('sender_type')->comment('0 from user to masafr, 1 from masafr to user, and else is th chat notifications');
            $table->integer('user_id');
            $table->integer('masafr_id');
            $table->text('subject')->nullable();
            $table->string('attach')->nullable();
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
        Schema::dropIfExists('messages');
    }
}
