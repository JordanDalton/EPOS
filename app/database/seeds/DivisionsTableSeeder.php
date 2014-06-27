<?php

class DivisionsTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		DB::table('divisions')->truncate();

		$data = array();

		$divisions = array(
			'Health Edco',
			'Health Impressions',
			'Special Impressions',
			'Childbirth Graphics',
			'Corporate'
		);

		for( $i = 0; $i < count($divisions); $i++ )
		{
			$data[$i] = array(
				'name' 			=> $divisions[$i],
				'created_at' 	=> new DateTime
			);
		}

		if( $data )  DB::table('divisions')->insert( $data );
	}

}