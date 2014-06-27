<?php namespace Acme\Repositories\Db;

use Acme\Item;
use Acme\Interfaces\Db\ItemRepositoryInterface;

class ItemRepository implements ItemRepositoryInterface {

	/**
	 * Get all of the models from the database.
	 *
	 * @param  array  $with 	Relationships to eager load.
	 * @param  array  $columns
	 * @return \Illuminate\Database\Eloquent\Collection|static[]
	 */
	public function all( $with = array() , $columns = array('*'))
	{
		return Item::with( $with )->get( $columns );
	}

	/**
	 * Create new resource instance.
	 * 
	 * @param  array  $data The data to be inserted into the model.
	 * @return Acme\Item
	 */
	public function instance( $data = array() )
	{
		return new Item($data);
	}
}