<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFiredretiresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('firedretires', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('employee_id');
            $table->string('code_employee');
            $table->date('dateIn');
            $table->date('dateOut');
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
        Schema::dropIfExists('firedretires');
    }
}
