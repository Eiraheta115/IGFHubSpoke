<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaysEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pays_employees', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('employee_id');
            $table->integer('pay_id');
            $table->string('code_employee');
            $table->decimal('baseSalary', 6, 2);
            $table->integer('days');
            $table->decimal('bond', 6, 2);
            $table->decimal('ISSS', 6, 2);
            $table->decimal('pension', 6, 2);
            $table->decimal('rent', 6, 2);
            $table->decimal('loans', 6, 2);
            $table->decimal('otherDiscounts', 6, 2);
            $table->decimal('otherIncomes', 6, 2);
            $table->decimal('total', 6, 2);
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
        Schema::dropIfExists('pays_employees');
    }
}
