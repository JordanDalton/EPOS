<?php

class UsersTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		DB::table('users')->truncate();

		$data = array(
			array(
				'display_name' 	=> 'Jordan Dalton', 
				'email' 		=> 'jordandalton@wrsgroup.com', 
				'first_name' 	=> 'Jordan', 
				'groups'		=> null,
				'last_name' 	=> 'Dalton', 
				'username' 		=> 'jdalton',
				'created_at' 	=> new DateTime
			)
		);

		if( $data )  DB::table('users')->insert( $data );
	}

}