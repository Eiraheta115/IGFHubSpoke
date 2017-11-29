<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('loantypes_id');
            $table->integer('employees_id');
            $table->string('code_employee');
            $table->string('code_loan');
            $table->date('deadline');
            $table->decimal('value', 6, 2);
            $table->decimal('fee', 6, 2);
            $table->decimal('debt', 6, 2);
            $table->boolean('payed');
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
        Schema::dropIfExists('loans');
    }
}
