<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->boolean('type')->default(0)->comment('0 from user to masafr  1 from masafr to user');
            $table->integer('masafr_id');
            $table->integer('user_id');
            $table->text('subject')->nullable();
            $table->boolean('wait')->default(0)->comment('0 there is no wait comments and 1 is there are and admin get the comments that need to be accepted by geting comments with wait is 1');
            $table->text('wait_subject')->nullable()->comment('the updated comment');
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
        Schema::dropIfExists('comments');
    }
}
