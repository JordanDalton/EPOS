<?php

use Acme\Interfaces\Db\PoRepositoryInterface as Po;

class TeamPoController extends \BaseController {

	/**
	 * The purchase order repositor implementation.
	 * 
	 * @var Acme\Repositories\Db\PoRepository
	 */
	protected $pos;

	/**
	 * Create new TeamPoController instance.
	 * 
	 * @param Po $pos 
	 * @return void 
	 */
	public function __construct( Po $pos )
	{
		// Implementations.
		// 
		$this->pos = $pos;
		
		// Execute BaseController::__construct();
		// 
		parent::__construct();
	}

	/**
	 * Display a listing of the pos submitted by subordinates of the user..
	 *
	 * @return Response
	 */
	public function index()
	{
		// Capture any search criteria from the query string.
		// 
		$q = Input::query('q', null);

		// Fetch the session user's purchase orders in paginated form.
		// 
		$pos = $q 
			? $this->pos->searchMyTeamPosPaginated( $q , 5, array('attachments', 'items'))
			: $this->pos->myTeamPos( true , 5, array('attachments', 'items'));

		// Assign content to the layout.
		// 
		$this->layout->content = View::make('team-pos.index');

		// Assign view prsenter.
		// 
		$this->layout->content->withPos(new PaginatedPresenterCollection('PoPresenter', $pos));
		$this->layout->content->withQuery($q);
	}
}