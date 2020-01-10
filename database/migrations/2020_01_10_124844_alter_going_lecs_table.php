<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterGoingLecsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('on_going_lecs', function (Blueprint $table) {
            $table->string('module_name');
            $table->string('lecture_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('on_going_lecs', function (Blueprint $table) {
            $table->dropColumn('module_name');
            $table->dropColumn('lecture_name');
        });
    }
}
