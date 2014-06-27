<?php namespace Acme\Services\Presentation;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\Paginator;
use Input;

class PaginatedPresenterCollection extends Paginator {

	protected $myPaginator;

	public function __construct($presenter, Paginator $paginator )
	{
		$this->setMyPaginator( $paginator );

		// Create a new collection form the paginator items.
		// 
		$collection = Collection::make($paginator->items);

		foreach( $collection as $key => $resource )
		{
			$collection->put($key, new $presenter($resource));
		}

		$this->items = $collection->toArray();
	}

	/**
	 * Get the pagination object.
	 *
	 * @return  Illuminate\Pagination\Paginator
	 */
	public function getMyPagintaor( )
	{
		return $this->myPaginator;	
	}

	/**
	 * Set the pagination links.
	 *
	 * @param  Illuminate\Pagination\Paginator $paginator
	 */
	public function setMyPaginator( Paginator $paginator )
	{
		$this->myPaginator = $paginator;	
	}

	/**
	 * Get the pagination links view.
	 *
	 * @param  string  $view
	 * @return \Illuminate\View\View
	 */
	public function links( $view = null )
	{
		$appends = Input::except('page');

		$paginator = $this->getMyPagintaor()->appends( $appends );

		$view = is_null($view) ? $paginator->env->getViewName() : $view;

		return $paginator->env->getPaginationView($paginator, $view);
	}
}