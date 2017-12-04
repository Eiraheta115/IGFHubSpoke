<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('salarytype_id');
            $table->integer('pensionType_id');
            $table->integer('job_id');
            $table->string('code');
            $table->boolean('active');
            $table->boolean('retired');
            $table->date('admition');
            $table->decimal('comission', 6, 2)->nullable();
            $table->string('bankaccount');
            $table->time('timeIn');
            $table->time('timeOut');
            $table->decimal('salary', 6, 2);
            $table->timestamps();
            $table->unique('code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
}
