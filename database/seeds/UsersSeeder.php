<?php

use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
			[		
	    		'id'			            => 1,
                'email' 		            => "15331274",
                'password'                  => bcrypt("123456"),
                'id_role'                   => 1,
                'updated_at' 	            => date('Y-m-d H:i:s'),
                'updated_at' 	            => date('Y-m-d H:i:s'),
  			]
        ]);
    }
}
