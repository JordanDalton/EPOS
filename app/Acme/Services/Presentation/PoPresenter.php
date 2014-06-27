<?php namespace Acme\Services\Presentation;

class PoPresenter extends Presenter {

	/**
	 * Modify which po identifying value we will
	 * use to publicly referance each purchase order.
	 * 
	 * @return string
	 */

	public function id()
	{
		return $this->resource->getPoIdentifier();
	}

	/**
	 * Modify how accountant_approved_at timestamps are presented.
	 * 
	 * @return string
	 */
	public function accountant_approved_at()
	{
		$accountant_approved_at = $this->resource->accountant_approved_at;

		return is_null($accountant_approved_at) ? $accountant_approved_at : $accountant_approved_at->format('m/d/Y @ h:ia');
	}

	/**
	 * Modify how created at timestamps are presented.
	 * 
	 * @return string
	 */
	public function created_at()
	{
		return $this->resource->created_at->format('m/d/Y @ h:ia');
	}

	/** 
	 * Return if a record is approved by both management and accounting.
	 * 
	 * @return boolean
	 */
	public function isApprovedComplete()
	{
		return $this->resource->isApprovedComplete();
	}

	public function items( $withTotal = false )
	{
		return $withTotal 
				? number_format($this->resource->items()->sum('total'), 2)
				: $this->resource->items();
	}

	public function manager_name()
	{
		// JSON decode the user objec.t
		// 
		$decoded = json_decode( $this->manager );

		// Return the manager's dipslay name.
		// 
		return isSet($decoded->display_name) ? $decoded->display_name : '';
	}

	/**
	 * Modify how manager_approved_at timestamps are presented.
	 * 
	 * @return string
	 */
	public function manager_approved_at()
	{
		$manager_approved_at = $this->resource->manager_approved_at;

		return is_null($manager_approved_at) ? $manager_approved_at : $manager_approved_at->format('m/d/Y @ h:ia');
	}
}