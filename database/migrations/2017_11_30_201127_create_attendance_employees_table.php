<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendanceEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_employees', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('attendance_id');
            $table->integer('employee_id');
            $table->string('code_employee');
            $table->dateTime('hourIn');
            $table->dateTime('hourOut');
            $table->boolean('forgiven');
            $table->string('observation')->nullable();
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
        Schema::dropIfExists('attendance_employees');
    }
}
