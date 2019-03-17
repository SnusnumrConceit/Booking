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
            $table->string('last_name')->comment('фамилия');
            $table->string('first_name')->comment('имя');
            $table->string('middle_name')->comment('отчество');
            $table->date('birthday')->comment('дата рождения');
            $table->unsignedInteger('appointment_id')->comment('должность');
            $table->timestamps();
        });

        Schema::table('employees', function (Blueprint $table) {
           $table->foreign('appointment_id')
               ->references('id')
               ->on('appointments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['appointment_id']);
        });

        Schema::dropIfExists('employees');
    }
}
