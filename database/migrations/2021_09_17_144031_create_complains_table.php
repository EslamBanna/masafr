<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complains', function (Blueprint $table) {
            $table->id();
            $table->enum('type',[1,2,3])->comment('1 admin 2 user 3 is masafr');
            $table->integer('user_id');
            $table->integer('masafr_id');
            $table->text('subject')->nullable();
            $table->string('attach')->nullable();
            $table->boolean('status')->comment('0 open 1 is close');
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
        Schema::dropIfExists('complains');
    }
}
