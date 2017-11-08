<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('people', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('civilstatus_id');
            $table->integer('peopleable_id');
            $table->string('peopleable_type');
            $table->string('fullname');
            $table->string('sex');
            $table->string('telephone');
            $table->string('cellphone');
            $table->string('email');
            $table->string('dui');
            $table->string('nit');
            $table->string('isss')->nullable();
            $table->date('birthday');
            $table->string('direction');
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
        Schema::dropIfExists('people');
    }
}
