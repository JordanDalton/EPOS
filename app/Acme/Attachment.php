<?php namespace Acme;

use Auth, Eloquent;

class Attachment extends Eloquent {
	
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'attachments';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = array(
		'cloud_filename',
		'extension',
		'local_filename',
		'mime',
		'po_id',
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
			$record->cloud_filename = $record->generateCloudFilename($record->po).'.'.$record->extension;
			$record->user_id        = Auth::user()->id;
		});
	}

	/**
	 * Generate a unique po number.
	 */
	public function generateCloudFilename( $po )
	{
		// Capture the current date.
		// 
		$year  = date('Y');

		// Count how many purchase orders have been submitted during the $year.
		// 
		$count = self::withTrashed()->whereBetween('created_at', array("{$year}-01-01 00:00:00", "{$year}-12-31 23:59:59"))
					->wherePoId($po->id)
					 ->count();
		$count++;

		return "{$year}-{$po->id}-{$count}"; 
	}

	/** 
	 * Fetch the purchase order the attachment belongs to.
	 * 
	 * @return Acme\Po
	 */
	public function po()
	{
		return $this->belongsTo('Acme\Po');
	}
}