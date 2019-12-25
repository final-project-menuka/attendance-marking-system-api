<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOnGoingLecsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('on_going_lecs', function (Blueprint $table) {
            $table->string('id')->unique();
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->string('module_code');
            $table->string('lec_hall_id');
            $table->string('lec_hall_number');
            $table->string('mac_address');
            $table->decimal('batch');
            $table->string('course');
            $table->date('date');
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
        Schema::dropIfExists('on_going_lecs');
    }
}
