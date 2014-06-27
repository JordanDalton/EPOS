<?php namespace Acme\Services\Validation;

class LocationValidator extends Validator {

	/**
	 * Define validation rules.
	 * 
	 * @var array
	 */
	public static $rules = array(
		'po.locations' => 'Array'
	);

	/**
	 * Validate the input data against validation rules.
	 * 
	 * @param  array $input The input data.
	 * @return boolean        
	 */
	public function validate( $input )
	{
		$getSelectedLocations = array_get($input, 'po.locations');

		// We will dynamically set validation rules for each location
		// that was selected.
		// 
		if( count($getSelectedLocations) )
		{
			foreach( $getSelectedLocations as $key => $value )
			{
				$selectionNumber = $key + 1;
				$selectionNumber = addOrdinalNumberSuffix($selectionNumber);

				$this->setRule("po.locations.$key", "exists:locations,id");
				$this->setMessage("po.locations.$key.exists", "Your $selectionNumber location selection is invalid.");
			}

			// Execute Validator::validate($input);
			// 
			return parent::validate( $input );
		}
	}
}