<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            // $table->foreign('id')->references('id')->on('customers')->onDelete('cascade');
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('condition')->default(1);
            $table->integer('id_role')->unsigned();
            $table->foreign('id_role')->references('id')->on('roles')->onDelete('cascade');
            $table->rememberToken();
            $table->timestamps();
        });

        // DB::table('users')->insert(array('id'=>'1', 'email' => 'admin@gmail.com', 'password'=>'Test123!', 'id_role'=>'1'));
        //  DB::table('users')->insert(array('id'=>'1', 'email' => 'admin@hotmail.com', 'password'=>$a, 'id_role'=>'1'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}