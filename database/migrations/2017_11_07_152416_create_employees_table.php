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
            $table->string('code');
            $table->boolean('active');
            $table->boolean('retired');
            $table->date('admition');
            $table->date('retirement');
            $table->decimal('comission', 6, 2)->nullable();
            $table->string('bankaccount');
            $table->boolean('day31');
            $table->integer('job_id');
            $table->decimal('salary', 6, 2);
            $table->integer('salarytype_id');
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
        Schema::dropIfExists('employees');
    }
}
