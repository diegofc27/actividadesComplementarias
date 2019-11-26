<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_teacher')->unsigned();
            $table->foreign('id_teacher')->references('id')->on('teachers')->onDelete('cascade');
            $table->string('name')->unique();
            $table->string('schedule');
            $table->integer('max_students');
            $table->integer('num_students')->default(0);            
            $table->integer('id_activity')->unsigned();
            $table->foreign('id_activity')->references('id')->on('activities')->onDelete('cascade');
            $table->boolean('condition')->default(1);
            $table->string('period', 5)->default(date('Y').(date('n') <= 6 ? '1':'2'));
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
        Schema::dropIfExists('groups');
    }
}
