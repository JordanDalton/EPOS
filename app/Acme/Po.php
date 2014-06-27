<?php namespace Acme;

use Auth, Eloquent, Event;

class Po extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'pos';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = array(
		'draft',
		'manager',
		'name',
		'po_number',
		'ship_to',
		'vendor'
	);

	/**
	 * The field from which we will identify the po record by.
	 * 
	 * @var string
	 */
	static $identifier = 'po_number';

	/**
	 * Indicates if the model should soft delete.
	 *
	 * @var bool
	 */
	protected $softDelete = true;	

	/**
	 * The attributes that should be mutated to dates.
	 *
	 * @var array
	 */
	protected $dates = array(
		'accountant_approved_at',
		'manager_approved_at'
	);

	/**
	 * The "booting" method of the model.
	 *
	 * @return void
	 */
	public static function boot()
	{
		// Execute Eloquent::boot();
		// 
		parent::boot();

		static::creating( function( $record )
		{
			$record->po_number     = $record->generatePoNumber();
			$record->submitter_id  = Auth::user()->id;
		});

		static::created( function( $record )
		{
			if( $record->draft == 0 )
			{
				Event::fire('po.created', array($record));			
			}
		});

		static::updated( function( $record )
		{
			$original_draft = $record->getOriginal('draft', 0);

			if( $original_draft == 1 && $record->draft == 0 )
			{
				Event::fire('po.updated', array($record));
			}
		});

		static::deleting( function( $record )
		{
			// Delete related records.
			// 
			$record->attachments()->delete();
			$record->divisions()->delete();
			$record->items()->delete();
			$record->locations()->delete();
		});
	}

	/**
	 * Generate a unique po number.
	 */
	public function generatePoNumber()
	{
		// Capture the current date.
		// 
		$year  = date('Y');

		// Count how many purchase orders have been submitted during the $year.
		// 
		$count = self::withTrashed()->whereBetween('created_at', array("{$year}-01-01 00:00:00", "{$year}-12-31 23:59:59"))->count();
		$count++;

		return "{$year}-{$count}"; 
	}

	/**
	 * Return the purchaes order's identification value.
	 * 
	 * @return int|string
	 */
	public function getPoIdentifier()
	{
		return $this->attributes[ static::$identifier ];
	}

	/**
	 * Return the purchaes order's identification field name.
	 * 
	 * @return int|string
	 */
	public function getPoIdentifierFieldName()
	{
		return static::$identifier;
	}

	/**
	 * Define query scope that will only fetch pos that are not marked as draft.
	 */
	public function scopeNonDraft($query)
	{
		$query->whereDraft(0);
	}

	/**
	 * Define query scope to fetch records where the user is an accountant.
	 */
	public function scopeAccountant($query, $accountant_id = false)
	{
		return $query->whereAccountantId( $accountant_id ? $accountant_id : Auth::user()->id );
	}

	/**
	 * Define query scope to fetch by the manager.
	 */
	public function scopeManager($query, $manager = false)
	{
		return $query->whereManager( $manager ? $manager : Auth::user()->display_name );
	}

	/** 
	 * Define query scope that will fetch only records that have been approved by accounting.
	 */
	public function scopeApprovedByAccounting($query)
	{
		return $query->whereNotNull('accountant_approved_at');
	}

	/** 
	 * Define query scope that will only return the po records where the in-session user approved the purchase
	 * order at the accounting level.
	 */
	public function scopeApprovedByAccountingUser($query, $id = false )
	{
		// Set the user id;
		// 
		$user_id = $id ? $id : Auth::user()->id;

		return $query->whereAccountantId( $user_id );
	}

	/** 
	 * Define query scope that will fetch only records that have been approved by management.
	 */
	public function scopeApprovedByManagement($query)
	{
		return $query->whereNotNull('manager_approved_at');
	}

	/** 
	 * Define query scipe that will only fetch po records where the "manager_approved_at" field is null.
	 */
	public function scopePendingApprovalByManagement( $query )
	{
		return $query->whereNull('manager_approved_at');
	}

	/** 
	 * Define query scipe that will only fetch po records that have not been approved by accounting.
	 */
	public function scopePendingApprovalByAccounting( $query )
	{
		return $query->whereNull('accountant_approved_at');
	}

	/**
	 * Define query scope to fetch by the session user's id number.
	 */
	public function scopeMine($query)
	{
		return $query->whereSubmitterId( Auth::user()->id );
	}

	/** 
	 * Fetch the user account of the accountant who will be approving the purchase order.
	 * 
	 * @return Acme\User
	 */
	public function accountant()
	{
		return $this->belongsTo('Acme\User', 'accountant_id');
	}

	/** 
	 * Fetch all attachments 
	 * 
	 * @return Acme\Attachment.
	 */
	public function attachments()
	{
		return $this->hasMany('Acme\Attachment');
	}

	/**
	 * Fetch all divisions assigned to the purchase order.
	 * 
	 * @return Acme\Division
	 */
	public function divisions()
	{
		return $this->belongsToMany('Acme\Division', 'po_divisions');
	}

	/** 
	 * Determine if the record is already been approved by management and accounting.
	 * @return boolean
	 */
	public function isApprovedComplete()
	{
		$accountant_approved = ! is_null($this->accountant_approved_at);
		$manager_approved    = ! is_null($this->manager_approved_at);

		// Return the status
		// 
		return ( $accountant_approved && $manager_approved );
	}

	/** 
	 * Determine if the user is a accounting or administration member.
	 * 
	 * @return boolean 
	 */
	public function isAllowedUser()
	{
		// List of allowable user ids.
		// 
		$allowables = array( $this->user_id );

		// Filter out any null values.
		// 
		$allowables = array_filter( $allowables , 'strlen' );

		// Check if the user is on the list of users allowed to view this purchase order.
		// 
		$allowed = ( in_array(Auth::user()->id, $allowables) OR (bool) Auth::user()->isAdmin() );

		// Return if the user is allowed.
		// 
		return $allowed;
	}

	/**
	 * Fetch all items assigned to the purchase order.
	 * 
	 * @return Acme\Item
	 */
	public function items()
	{
		return $this->hasMany('Acme\Item');
	}

	/**
	 * Fetch all locations assigned to the purchase order.
	 * 
	 * @return Acme\Location
	 */
	public function locations()
	{
		return $this->belongsToMany('Acme\Location', 'po_locations');
	}

	/**
	 * Fetch the user account of the user that submitted the purchase order.
	 * 
	 * @return Acme\User
	 */
	public function submitter()
	{
		return $this->belongsTo('Acme\User', 'submitter_id');
	}
}