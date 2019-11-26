<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQ8Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('q8_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nameQ8', 3)->unique();
            $table->string('period', 5)->default(date('Y').(date('n') <= 6 ? '1':'2'));
            $table->integer('num_students')->default(0);
            $table->boolean('condition')->default(1);
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
        Schema::dropIfExists('q8_groups');
    }
}
