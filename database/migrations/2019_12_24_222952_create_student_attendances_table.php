<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_attendances', function (Blueprint $table) {
            $table->bigIncrements('id')->unique();
            $table->dateTime('attended_time');
            $table->string('student_id');
            $table->string('module_code');
            $table->string('lec_hall_id');
            $table->string('lec_hall_num');
            $table->string('student_name');
            $table->string('mac_address');
            $table->tinyInteger('late');
            $table->tinyInteger('half_leave')->default(0);
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
        Schema::dropIfExists('student_attendances');
    }
}
