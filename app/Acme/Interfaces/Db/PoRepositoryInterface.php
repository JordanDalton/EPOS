<?php namespace Acme\Interfaces\Db;

interface PoRepositoryInterface {
	
	/**
	 * Update resource record to show that it was updated by an accountant.
	 * 
	 * @param  int|string  $id   The po record's identifier.
	 * @return boolean
	 */
	public function accountantApproved( $id );

	/** 
	 * Fetch purchase orders that have been approved by accounting.
	 * 
	 * @param  array   $with    Relational data to eager-load.
	 * @param  array   $columns Columnns to return in the results.
	 * @param  boolean $user    (Optional) is the id of the user.
	 * @return Illuminate\Pagination\Paginator
	 */
	public function accountingApprovedArchives( $with = array(), $columns = array('*'), $user = false );

	/** 
	 * Fetch purchase orders that have been approved by accounting.
	 * 
	 * @param int      $perPage The number of records to display on each page.
	 * @param  array   $with    Relational data to eager-load.
	 * @param  array   $columns Columnns to return in the results.
	 * @param  boolean $user    (Optional) is the id of the user.
	 * @return Illuminate\Pagination\Paginator
	 */
	public function accountingApprovedArchivesPaginated( $perPage = 15, $with = array(), $columns = array('*'), $user = false );

	/** 
	 * Fetch purchase orders that we approved (at accounting level) by the user that is in-session.
	 * @param  array   $with    Relational data to eager-load.
	 * @param  array   $columns Columnns to return in the results.
	 * @param  boolean $user    (Optional) is the id of the user.
	 * @return Illuminate\Pagination\Paginator
	 */
	public function approvedByAccountingUser( $with = array(), $columns = array('*'), $user = false );

	/** 
	 * Fetch purchase orders (in paginated form) that we approved (at accounting level) by the user that is in-session.
	 * 
	 * @param  array   $with    Relational data to eager-load.
	 * @param  array   $columns Columnns to return in the results.
	 * @param  boolean $user    (Optional) is the id of the user.
	 * @return Illuminate\Pagination\Paginator
	 */
	public function approvedByAccountingUserPaginated( $perPage = 15, $with = array(), $columns = array('*'), $user = false );

	/**
	 * Create new resource record.
	 * 
	 * @param  array  $data The user input data.
	 * @return \Illuminate\Database\Eloquent\Model|static|null
	 */
	public function create( $data = array() );

	/**
	 * Find a model by its primary key.
	 *
	 * @param  mixed  $id
	 * @param  array  $columns
	 * @param  array  $with
	 * @return \Illuminate\Database\Eloquent\Model|static|null
	 */
	public function find( $id, $with = array(), $columns = array('*'));

	/**
	 * Update resource record to show that it was updated by an accountant.
	 * 
	 * @param  int|string  $id      The po record's identifier.
	 * @param  string      $manager The manager's name.
	 * @return boolean
	 */
	public function managerApproved( $id, $manager );

	/**
	 * Fetch all purchase orders of the user in session.
	 * 
	 * @param boolean $paginate Return results in paginated form.
	 * @param int     $perPage  The number of records to show per page.
	 * @param array   $with     Relationships to eager-load
	 * @param array   $columns
	 * @return Illuminate\Pagination\Paginator
	 */
	public function myPos( $paginate = false, $perPage = 15, array $with = array(), $columns = array('*'));

	/**
	 * Fetch all purchase orders of the user's subordinates.
	 * 
	 * @param boolean $paginate Return results in paginated form.
	 * @param int     $perPage  The number of records to show per page.
	 * @param array   $with     Relationships to eager-load
	 * @param array   $columns
	 * @return Illuminate\Pagination\Paginator
	 */
	public function myTeamPendingPos( $paginate = false, $perPage = 15, array $with = array(), $columns = array('*'));

	/**
	 * Fetch all purchase orders of the user's subordinates.
	 * 
	 * @param boolean $paginate Return results in paginated form.
	 * @param int     $perPage  The number of records to show per page.
	 * @param array   $with     Relationships to eager-load
	 * @param array   $columns
	 * @return Illuminate\Pagination\Paginator
	 */
	public function myTeamPos( $paginate = false, $perPage = 15, array $with = array(), $columns = array('*'));

	/**
	 * Search through all purchase orders submitted by the user's subordinates.
	 * 
	 * @param midex   $criteria The search criteria.
	 * @param boolean $paginate Return results in paginated form.
	 * @param int     $perPage  The number of records to show per page.
	 * @param array   $with     Relationships to eager-load
	 * @param array   $columns
	 * @return Illuminate\Pagination\Paginator
	 */
	public function searchMyTeamPosPaginated( $criteria = null, $perPage = 15, array $with = array(), $columns = array('*'));

	/** 
	 * Fetch records that are approved by management and are awaiting accountings approval.
	 *
	 * @param  array   $with    Relation data to eager-load.
	 * @param  array   $columns The columns that need to be returned.
	 * @return Collection
	 */
	public function pendingAccountingApproval( $with = array(), $columns = array('*') );

	/** 
	 * Fetch records (in pagination form) that are approved by management and are awaiting accountings approval.
	 *
	 * @param  integer $perPage The number of records to show per page.
	 * @param  array   $with    Relation data to eager-load.
	 * @param  array   $columns The columns that need to be returned.
	 * @return Collection
	 */
	public function pendingAccountingApprovalPaginated( $perPage = 15, $with = array(), $columns = array('*') );

	/**
	 * Search through all purchase orders submitted by the user.
	 * 
	 * @param midex   $criteria The search criteria.
	 * @param boolean $paginate Return results in paginated form.
	 * @param int     $perPage  The number of records to show per page.
	 * @param array   $with     Relationships to eager-load
	 * @param array   $columns
	 * @return Illuminate\Pagination\Paginator
	 */
	public function searchMyPosPaginated( $criteria = null, $perPage = 15, array $with = array(), $columns = array('*'));

	/**
	 * Update resource record.
	 * 
	 * @param  int|string  $id   The po record's identifier.
	 * @param  array       $data The user input data.
	 * @return \Illuminate\Database\Eloquent\Model|static|null
	 */
	public function update( $id , $data = array() );
}