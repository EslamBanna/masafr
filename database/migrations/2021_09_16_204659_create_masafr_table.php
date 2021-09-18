<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasafrTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('masafr', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('photo')->nullable();
            $table->double('rate')->default(0);
            $table->string('id_Photo')->nullable();
            $table->boolean('gender')->default(1)->comment('0 => female  1 => male');
            $table->string('email')->unique();
            $table->string('country_code');
            $table->string('phone')->unique();
            $table->string('validation_code')->nullable();
            // $table->string('validation_code')->unique();
            $table->boolean('active')->default(0)->comment('0 => no  1 => yes');
            $table->integer('active_try')->default(0)->comment('how many he try to send a verfication code to mobile phone');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('national_id_number')->nullable();
            $table->string('nationality')->nullable();
            $table->integer('car_id')->nullable();
            $table->string('car_name')->nullable();
            $table->string('car_model')->nullable();
            $table->string('car_number')->nullable();
            $table->string('driving_license_photo')->nullable();
            $table->rememberToken();
            $table->integer('trips_count')->default(0)->comment('number of trips finished');
            $table->integer('bargains_count')->default(0)->comment('safakat count');
            $table->integer('negative_points_count')->default(0)->comment('Negative points count');
            $table->boolean('sms_notifications')->default(0)->comment('0 => no  1 => yes');
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
        Schema::dropIfExists('masafr');
    }
}
