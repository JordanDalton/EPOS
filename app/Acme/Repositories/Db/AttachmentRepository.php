<?php namespace Acme\Repositories\Db;

use Acme\Attachment;
use Acme\Interfaces\Db\AttachmentRepositoryInterface;

class AttachmentRepository implements AttachmentRepositoryInterface {

	/**
	 * Create new resource record.
	 * 
	 * @param  array  $data The user input data.
	 * @return \Illuminate\Database\Eloquent\Model|static|null
	 */
	public function create( $data = array() )
	{
		return Attachment::create($data);
	}

	/**
	 * Create new resource record.
	 * 
	 * @param  array  $data The user input data.
	 * @return \Illuminate\Database\Eloquent\Model|static|null
	 */
	public function destroy( $id )
	{
		return Attachment::find($id)->delete();
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
		return Attachment::with( $with )->find($id, $columns );
	}

	/** 
	 * Fetch all attachments for a given purchase order.
	 * 
	 * @param  int|string $po_number The purchase order identifier.
	 * @param  array  $with          Realtionships to eager-load.
	 * @param  array  $columns       Restrict which columns to include in the return.
	 * @return Acme\Attachment
	 */
	public function getByPo( $po_number , array $with = array() , array $columns = array('*'))
	{
		return Attachment::with( $with )->wherePoId($po_number)->get();
	}

	/**
	 * Create new instance.
	 * 
	 * @param  array  $data The user input data.
	 * @return \Illuminate\Database\Eloquent\Model|static|null
	 */
	public function instance( $data = array() )
	{
		return new Attachment($data);
	}
}