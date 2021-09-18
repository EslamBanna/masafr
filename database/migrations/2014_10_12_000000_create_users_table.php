<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('photo')->nullable();
            $table->double('rate')->default(0);
            $table->string('id_Photo')->nullable();
            $table->string('national_id_number')->nullable();
            $table->boolean('gender')->default(1)->comment('0 => female  1 => male');
            $table->string('email')->unique();
            $table->string('country_code');
            $table->string('phone')->unique();
            // $table->string('validation_code')->unique();
            $table->string('validation_code')->nullable();
            $table->boolean('active')->default(0)->comment('0 => no  1 => yes');
            $table->integer('active_try')->default(0)->comment('how many he try to send a verfication code to mobile phone');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->integer('orders_count')->default(0);
            $table->integer('bargains_count')->default(0);
            $table->boolean('email_notifications')->default(0)->comment('0 => no  1 => yes');
            $table->integer('balance')->default(0);
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
        Schema::dropIfExists('users');
    }
}
