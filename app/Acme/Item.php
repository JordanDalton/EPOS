<?php namespace Acme;

use Eloquent;

class Item extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'items';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = array(
		'description', 
		'due_date', 
		'qty', 
		'tax', 
		'total',
		'uc', 
		'uc_um', 
		'um', 
	);
	
	/**
	 * Indicates if the model should soft delete.
	 *
	 * @var bool
	 */
	protected $softDelete = true;	
	
	/**
	 * Format to a shorter float.
	 * 
	 * @param  number $value 
	 * @return float
	 */
	public function getTotalAttribute($value)
	{
		return number_format($value, 2);
	}
}