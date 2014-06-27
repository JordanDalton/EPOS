<?php namespace Acme\Services\Validation;

class PoValidator extends Validator {

	/**
	 * Define validation rules.
	 * 
	 * @var array
	 */
	public static $rules = array(
		'name' => 'required|min:3'
	);

	/**
	 * Validate the input data against validation rules.
	 * 
	 * @param  array $input The input data.
	 * @return boolean        
	 */
	public function validate( $input )
	{
		// Ignore input data that is not relevant to this portion of of the
		// verification process.
		// 
		$input = array_except( $input, array('divisions', 'locations', 'items'));

		// Execute Validator::validate($input).
		// 
		return parent::validate( $input );
	}
}