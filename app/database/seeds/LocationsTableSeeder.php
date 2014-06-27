<?php

class LocationsTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		DB::table('locations')->truncate();

		$data = array();

		$locations = array(
			'5045 Franklin Ave., Waco, Texas 76710',
			'624 Texas Central Parkway, Waco, Texas 76712',
		);

		for( $i = 0; $i < count($locations); $i++ )
		{
			$data[$i] = array(
				'address' 	=> $locations[$i],
				'created_at'=> new DateTime
			);
		}

		if( $data )  DB::table('locations')->insert( $data );
	}

}