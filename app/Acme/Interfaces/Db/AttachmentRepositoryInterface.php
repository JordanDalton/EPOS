<?php namespace Acme\Interfaces\Db;

interface AttachmentRepositoryInterface {

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
	 * Fetch all attachments for a given purchase order.
	 * 
	 * @param  int|string $po_number The purchase order identifier.
	 * @param  array  $with          Realtionships to eager-load.
	 * @param  array  $columns       Restrict which columns to include in the return.
	 * @return Acme\Attachment
	 */
	public function getByPo( $po_number , array $with = array() , array $columns = array('*'));

	/**
	 * Create new instance.
	 * 
	 * @param  array  $data The user input data.
	 * @return \Illuminate\Database\Eloquent\Model|static|null
	 */
	public function instance( $data = array() );
}