<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_user')->unsigned();
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->string('period', 5)->default(date('Y').(date('n') <= 6 ? '1':'2'));
            $table->string('paternal_surname');
            $table->string('maternal_surname')->nullable();
            $table->string('name');
            $table->integer('id_group')->unsigned()->nullable();
            $table->foreign('id_group')->references('id')->on('groups')->onDelete('cascade');
            $table->integer('id_q8')->unsigned();
            $table->foreign('id_q8')->references('id')->on('q8_groups')->onDelete('cascade');
            $table->boolean('condition')->default(1);
            $table->string('grade');
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
        Schema::dropIfExists('students');
    }
}
