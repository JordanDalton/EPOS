<?php namespace Acme\Interfaces\CloudStorage;

interface CloudStorageInterface {

	/**
	 * Retrieves objects from cloud storage.
	 * 
	 * @param  string  $key    The filename to save the file as when it reaches the cloud.
	 * @param  boolean $bucket (Optional) The name of the bucket we want to put the file in.
	 */
	public function get( $key, $bucket );

	/** 
	 * Adds an object to a bucket.
	 * 
	 * @param  string  $key    The filename to save the file as when it reaches the cloud.
	 * @param  string  $source The filepath the local file.
	 * @param  boolean $bucket (Optional) The name of the bucket we want to put the file in.
	 */
	public function put( $key, $source, $bucket  = false);
}