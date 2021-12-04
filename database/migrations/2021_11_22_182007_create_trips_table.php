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
            $table->datetime('starts_at');
            $table->unsignedBigInteger('governorate_id');
            $table->string('departure');
            $table->string('destination');
            $table->text('details');
            $table->integer('number_of_seats');
            $table->double('estimated_time');
            $table->string('car_plate');
            $table->double('cost');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->integer('status')->default(0); // 0 =>  available | 1 => canceled
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
