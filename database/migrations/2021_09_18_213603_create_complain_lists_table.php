<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplainListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complain_lists', function (Blueprint $table) {
            $table->id();
            $table->enum('type',[0,1,2])->comment('0 user 1 is masafr 2 is admin');
            $table->string('subject')->nullable();
            $table->string('attach')->nullable();
            $table->integer('complain_id');
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
        Schema::dropIfExists('complain_lists');
    }
}
