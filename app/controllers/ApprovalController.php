<?php

use Acme\Interfaces\Db\PoRepositoryInterface as Po;

class ApprovalController extends \BaseController {

	/** 
	 * Create new ApprovalController
	 *
	 * @return void 
	 */
	public function __construct( Po $pos )
	{
		$this->pos = $pos;

		// Execute BaseController::__construct();
		// 
		parent::__construct();
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		// Fetch purchase order records that have been approved by management but have yet to be approved by accounting.
		// 
		$pos = $this->pos->pendingAccountingApprovalPaginated();

		// Assign content to the layout.
		// 
		$this->layout->content = View::make('approvals.index')->withPos(new PaginatedPresenterCollection('PoPresenter', $pos));
	}

	/** 
	 * Display a listing of the resources that have been archived.
	 *
	 * @return Response
	 */
	public function archives()
	{
		// Capture any search criteria from the query string.
		// 
		$q = Input::query('q', null);

		// Fetch the session user's purchase orders in paginated form.
		// 
		$pos = $q 
			? $this->pos->searchAccountingApprovedArchivesPaginated( $q , 15, array('attachments', 'items'))
			: $this->pos->accountingApprovedArchivesPaginated( 15, array('attachments', 'items'));

		// Assign content to the layout.
		// 
		$this->layout->content = View::make('approvals.archives')->withPos(new PaginatedPresenterCollection('PoPresenter', $pos));
	}

	/**
	 * Display a listing of the resource that the in-session user approved.
	 *
	 * @return Response
	 */
	public function my()
	{
		// Fetch purchase order records that have been approved by management but have yet to be approved by accounting.
		// 
		$pos = $this->pos->approvedByAccountingUserPaginated();

		// Assign content to the layout.
		// 
		$this->layout->content = View::make('approvals.my')->withPos(new PaginatedPresenterCollection('PoPresenter', $pos));
	}
}