<?php namespace Acme\Services\Validation;

class DivisionValidator extends Validator {

	/**
	 * Define validation rules.
	 * 
	 * @var array
	 */
	public static $rules = array(
		'po.divisions' => 'Array'
	);

	/**
	 * Validate the input data against validation rules.
	 * 
	 * @param  array $input The input data.
	 * @return boolean        
	 */
	public function validate( $input )
	{
		$getSelectedDivisions = array_get($input, 'po.divisions');

		// We will dynamically set validation rules for each division
		// that was selected.
		// 
		if( count($getSelectedDivisions) )
		{
			foreach( $getSelectedDivisions as $key => $value )
			{
				$selectionNumber = $key + 1;
				$selectionNumber = addOrdinalNumberSuffix($selectionNumber);

				$this->setRule("po.divisions.$key", "exists:divisions,id");
				$this->setMessage("po.divisions.$key.exists", "Your $selectionNumber division selection is invalid.");
			}

			// Execute Validator::validate($input);
			// 
			return parent::validate( $input );
		}
	}
}