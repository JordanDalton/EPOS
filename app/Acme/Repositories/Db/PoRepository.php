<?php namespace Acme\Repositories\Db;

use Acme\Interfaces\Db\ItemRepositoryInterface as Item;
use Acme\Interfaces\Db\PoRepositoryInterface;
use Acme\Po;
use Auth;
use DateTime;
use Event;

class PoRepository implements PoRepositoryInterface {

	/**
	 * The item repository implementation.
	 * 
	 * @var Acme\Repositories\Db\ItemRepository
	 */
	protected $items;

	/**
	 * Create new PoRepository instance.
	 * 
	 * @param Item $items
	 * @return void
	 */
	public function __construct( Item $items )
	{
		$this->items = $items;
	}

	/**
	 * Update resource record to show that it was updated by an accountant.
	 * 
	 * @param  int|string  $id   The po record's identifier.
	 * @return boolean
	 */
	public function accountantApproved( $id )
	{
		// Fetch the po record.
		// 
		$record = $this->find($id);

		// Add the manager approved at timestamp.
		// 
		$record->accountant_approved_at = new DateTime;
		$record->accountant_id = Auth::user()->id;

		// Trigger accountant approved event.
		// 
		Event::fire('po.accountant.approved', array($record));

		// Update the record.
		// 
		return $record->save();
	}

	/** 
	 * Fetch purchase orders that have been approved by accounting.
	 * 
	 * @param int      $perPage The number of records to display on each page.
	 * @param  array   $with    Relational data to eager-load.
	 * @param  array   $columns Columnns to return in the results.
	 * @param  boolean $user    (Optional) is the id of the user.
	 * @return Illuminate\Pagination\Paginator
	 */
	public function accountingApprovedArchives( $with = array(), $columns = array('*'), $user = false )
	{
		return Po::with($with)
				 ->nonDraft()
				 ->approvedByAccounting()
				 ->orderBy('id', 'desc')
				 ->get( $columns );
	}

	/** 
	 * Fetch purchase orders that have been approved by accounting.
	 * 
	 * @param int      $perPage The number of records to display on each page.
	 * @param  array   $with    Relational data to eager-load.
	 * @param  array   $columns Columnns to return in the results.
	 * @param  boolean $user    (Optional) is the id of the user.
	 * @return Illuminate\Pagination\Paginator
	 */
	public function accountingApprovedArchivesPaginated( $perPage = 15, $with = array(), $columns = array('*'), $user = false )
	{
		return Po::with($with)
				 ->nonDraft()
				 ->approvedByAccounting()
				 ->orderBy('id', 'desc')
				 ->paginate( $perPage, $columns );
	}

	/** 
	 * Fetch purchase orders that we approved (at accounting level) by the user that is in-session.
	 * @param  array   $with    Relational data to eager-load.
	 * @param  array   $columns Columnns to return in the results.
	 * @param  boolean $user    (Optional) is the id of the user.
	 * @return Illuminate\Pagination\Paginator
	 */
	public function approvedByAccountingUser( $with = array(), $columns = array('*'), $user = false )
	{
		return Po::with($with)
				 ->nonDraft()
				 ->approvedByManagement()
				 ->approvedByAccounting()
				 ->approvedByAccountingUser( $user )
				 ->get( $columns );
	}

	/** 
	 * Fetch purchase orders (in paginated form) that we approved (at accounting level) by the user that is in-session.
	 *
	 * @param int      $perPage The number of records to display on each page.
	 * @param  array   $with    Relational data to eager-load.
	 * @param  array   $columns Columnns to return in the results.
	 * @param  boolean $user    (Optional) is the id of the user.
	 * @return Illuminate\Pagination\Paginator
	 */
	public function approvedByAccountingUserPaginated( $perPage = 15, $with = array(), $columns = array('*'), $user = false )
	{
		return Po::with($with)
				 ->nonDraft()
				 ->approvedByManagement()
				 ->approvedByAccounting()
				 ->approvedByAccountingUser( $user )
				 ->paginate( $perPage, $columns );
	}

	/**
	 * Create new resource record.
	 * 
	 * @param  array  $data The user input data.
	 * @return \Illuminate\Database\Eloquent\Model|static|null
	 */
	public function create( $data = array() )
	{
		// Separate the main po data from the rest of the data
		// that was submitted.
		// 
		$po = array_except($data, array('po'));

		// Separate the division data from the rest of the data
		// that was submitted.
		// 
		$divisions = array_get($data, 'po.divisions');

		// Separate the location data from the rest of the data
		// that was submitted.
		// 
		$locations = array_get($data, 'po.locations');

		// Separate the item data from the rest of the data
		// that was submitted.
		// 
		$items = array_get($data, 'po.items');

		$thisItems = $this->items;

		// Create item instances
		// 
		$items = array_map( function( $record ) use ($thisItems)
		{
			// Remove any blank fields
			// 
			$record = array_filter($record, 'strlen');

			// Return new item instance.
			// 
			return $thisItems->instance($record);

		}, $items);

		// Generate new PO record.
		// 
		$po = Po::create($po);

		// Generate and attach items to the PO record.
		// 
		$po->items()->saveMany($items);         

		// Attach divisions to the po (if applicable).
		// 
		if( is_array($divisions)) $po->divisions()->sync($divisions);  

		// Attach locations to the po (if applicable).
		// 
		if( is_array($locations)) $po->locations()->sync($locations); 

		// Now return the purchase order record.
		// 
		return $po;
	}

	/**
	 * Find a model by its primary key.
	 *
	 * @param  mixed  $id
	 * @param  array  $with
	 * @param  array  $columns
	 * @return \Illuminate\Database\Eloquent\Model|static|null
	 */
	public function find( $id, $with = array(), $columns = array('*'))
	{
		return Po::with( $with )->where( Po::$identifier , '=' , $id )->first( $columns );
	}

	/**
	 * Update resource record to show that it was updated by an accountant.
	 * 
	 * @param  int|string  $id      The po record's identifier.
	 * @param  string      $manager The manager's name.
	 * @return boolean
	 */
	public function managerApproved( $id, $manager )
	{
		// Fetch the po record.
		// 
		$record = $this->find($id);

		// Assign the manager's name to the record.
		// 
		$record->manager = $manager;

		// Add the manager approved at timestamp.
		// 
		$record->manager_approved_at = new DateTime;

		// Trigger manager approved event.
		// 
		Event::fire('po.manager.approved', array($record));

		// Update the record.
		// 
		return $record->save();
	}

	/**
	 * Fetch all purchase orders of the user in session.
	 * 
	 * @param boolean $paginate Return results in paginated form.
	 * @param int     $perPage  The number of records to show per page.
	 * @param array   $with     Relationships to eager-load
	 * @param array   $columns
	 * @return Illuminate\Pagination\Paginator
	 */
	public function myPos( $paginate = false, $perPage = 15, array $with = array(), $columns = array('*'))
	{
		$po = Po::with( $with )->mine()->orderBy('created_at', 'desc');

		return $paginate ? $po->paginate( $perPage , $columns) : $po->get( $columns );
	}

	/**
	 * Fetch all purchase orders of the user's subordinates.
	 * 
	 * @param boolean $paginate Return results in paginated form.
	 * @param int     $perPage  The number of records to show per page.
	 * @param array   $with     Relationships to eager-load
	 * @param array   $columns
	 * @return Illuminate\Pagination\Paginator
	 */
	public function myTeamPendingPos( $paginate = false, $perPage = 15, array $with = array(), $columns = array('*'))
	{
		$po = Po::with( $with )->nonDraft()->pendingApprovalByManagement()->orderBy('created_at', 'desc');

		return $paginate ? $po->paginate( $perPage , $columns) : $po->get( $columns );
	}

	/**
	 * Fetch all purchase orders of the user's subordinates.
	 * 
	 * @param boolean $paginate Return results in paginated form.
	 * @param int     $perPage  The number of records to show per page.
	 * @param array   $with     Relationships to eager-load
	 * @param array   $columns
	 * @return Illuminate\Pagination\Paginator
	 */
	public function myTeamPos( $paginate = false, $perPage = 15, array $with = array(), $columns = array('*'))
	{
		$po = Po::with( $with )->nonDraft()->manager()->orderBy('created_at', 'desc');

		return $paginate ? $po->paginate( $perPage , $columns) : $po->get( $columns );
	}

	/** 
	 * Fetch records that are approved by management and are awaiting accountings approval.
	 *
	 * @param  array   $with    Relation data to eager-load.
	 * @param  array   $columns The columns that need to be returned.
	 * @return Collection
	 */
	public function pendingAccountingApproval( $with = array(), $columns = array('*') )
	{
		return Po::with($with)
				  ->nonDraft()
				  ->pendingApprovalByAccounting()
				  ->get($columns);
	}

	/** 
	 * Fetch records (in pagination form) that are approved by management and are awaiting accountings approval.
	 *
	 * @param  integer $perPage The number of records to show per page.
	 * @param  array   $with    Relation data to eager-load.
	 * @param  array   $columns The columns that need to be returned.
	 * @return Collection
	 */
	public function pendingAccountingApprovalPaginated( $perPage = 15, $with = array(), $columns = array('*') )
	{
		return Po::with($with)
				 ->nonDraft()
				 ->pendingApprovalByAccounting()
				 ->paginate( $perPage , $columns);
	}

	/** 
	 * Search purchase orders that have been approved by accounting.
	 *
	 * @param midex    $criteria The search criteria.
	 * @param  array   $with     Relational data to eager-load.
	 * @param  array   $columns  Columnns to return in the results.
	 * @param  boolean $user     (Optional) is the id of the user.
	 * @return Illuminate\Pagination\Paginator
	 */
	public function searchAccountingApprovedArchives( $criteria = null, $with = array(), $columns = array('*'), $user = false )
	{
		// Separate the criteria into keywords.
		// 
		$keywords = filterKeywords( explode(' ', $criteria) );

		// Prepare our where statements that will be injected into the query.
		// 
		$where = function( $query ) use ( $keywords )
		{
			$counter = 0;
			foreach( $keywords as $keyword )
			{
				if( $counter == 0 )
				{
					$query->where('name', 'LIKE', "%$keyword%");
				}

				$query->orWhere('name', 'LIKE', "%$keyword%");
				$query->orWhere('vendor', 'LIKE', "%$keyword%");
				$counter++;
			}
		};

		return Po::with($with)
				 ->nonDraft()
				 ->approvedByAccounting()
				 ->where($where)
				 ->orderBy('id', 'desc')
				 ->get( $columns );
	}

	/** 
	 * Search purchase orders that have been approved by accounting.
	 *
	 * @param midex    $criteria The search criteria.
	 * @param int      $perPage  The number of records to display on each page.
	 * @param  array   $with     Relational data to eager-load.
	 * @param  array   $columns  Columnns to return in the results.
	 * @param  boolean $user     (Optional) is the id of the user.
	 * @return Illuminate\Pagination\Paginator
	 */
	public function searchAccountingApprovedArchivesPaginated( $criteria = null, $perPage = 15, $with = array(), $columns = array('*'), $user = false )
	{
		// Separate the criteria into keywords.
		// 
		$keywords = filterKeywords( explode(' ', $criteria) );

		// Prepare our where statements that will be injected into the query.
		// 
		$where = function( $query ) use ( $keywords )
		{
			$counter = 0;
			foreach( $keywords as $keyword )
			{
				if( $counter == 0 )
				{
					$query->where('name', 'LIKE', "%$keyword%");
				}

				$query->orWhere('name', 'LIKE', "%$keyword%");
				$query->orWhere('vendor', 'LIKE', "%$keyword%");
				$counter++;
			}
		};

		return Po::with($with)
				 ->nonDraft()
				 ->approvedByAccounting()
				 ->where($where)
				 ->orderBy('id', 'desc')
				 ->paginate( $perPage, $columns );
	}

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
	public function searchMyPosPaginated( $criteria = null, $perPage = 15, array $with = array(), $columns = array('*'))
	{
		// Separate the criteria into keywords.
		// 
		$keywords = filterKeywords( explode(' ', $criteria) );

		// Prepare our where statements that will be injected into the query.
		// 
		$where = function( $query ) use ( $keywords )
		{
			$counter = 0;
			foreach( $keywords as $keyword )
			{
				if( $counter == 0 )
				{
					$query->where('name', 'LIKE', "%$keyword%");
				}

				$query->orWhere('name', 'LIKE', "%$keyword%");
				$query->orWhere('vendor', 'LIKE', "%$keyword%");
				$counter++;
			}
		};

		return Po::with($with)
				 ->mine()
				 ->where($where)
				 ->orderBy('created_at', 'desc')
				 ->paginate( $perPage , $columns);
	}

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
	public function searchMyTeamPosPaginated( $criteria = null, $perPage = 15, array $with = array(), $columns = array('*'))
	{
		// Separate the criteria into keywords.
		// 
		$keywords = filterKeywords( explode(' ', $criteria) );

		// Prepare our where statements that will be injected into the query.
		// 
		$where = function( $query ) use ( $keywords )
		{
			$counter = 0;
			foreach( $keywords as $keyword )
			{
				if( $counter == 0 )
				{
					$query->where('name', 'LIKE', "%$keyword%");
				}

				$query->orWhere('name', 'LIKE', "%$keyword%");
				$query->orWhere('vendor', 'LIKE', "%$keyword%");
				$counter++;
			}
		};

		return Po::with($with)
				 ->nonDraft()
				 ->manager()
				 ->where($where)
				 ->orderBy('created_at', 'desc')
				 ->paginate( $perPage , $columns);
	}

	/**
	 * Update resource record.
	 * 
	 * @param  int|string  $id   The po record's identifier.
	 * @param  array       $data The user input data.
	 * @return \Illuminate\Database\Eloquent\Model|static|null
	 */
	public function update( $id , $data = array() )
	{
		// Fetch the purchase order record.
		// 
		$record = $this->find( $id , array('divisions'));

		// Separate the main po data from the rest of the data
		// that was submitted.
		// 
		$po = array_except($data, array('po'));

		// Separate the division data from the rest of the data
		// that was submitted.
		// 
		$divisions = array_get($data, 'po.divisions');

		// Separate the location data from the rest of the data
		// that was submitted.
		// 
		$locations = array_get($data, 'po.locations');

		// Separate the item data from the rest of the data
		// that was submitted.
		// 
		$items = array_get($data, 'po.items');

		// Create item instances
		// 
		$items = array_map( function( $record )
		{
			// Remove any blank fields
			// 
			$record = array_filter($record, 'strlen');

			// Return new item instance.
			// 
			return $this->items->instance($record);

		}, $items);

		// Delete all existing items.
		// 
		$record->items()->delete();

		// Generate and attach items to the PO record.
		// 
		$record->items()->saveMany($items);    
  
		// Attach divisions to the po (if applicable).
		// 
		if( is_array($divisions)) $record->divisions()->sync($divisions);  

		// Attach locations to the po (if applicable).
		// 
		if( is_array($locations)) $record->locations()->sync($locations); 

		// Save any changes made to the parent purchase order record.
		// 
		$record->fill( $data )->save();

		// Return the updated po record
		// 
		return $record;
	}
}