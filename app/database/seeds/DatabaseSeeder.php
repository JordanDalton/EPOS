<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		$this->call('DivisionsTableSeeder');
		$this->call('LocationsTableSeeder');
		// $this->call('UsersTableSeeder');
	}

}