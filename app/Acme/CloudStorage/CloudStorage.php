<?php namespace Acme\CloudStorage;

use Acme\Interfaces\CloudStorage\CloudStorageInterface;

class CloudStorage {

	/**
	 * The cloud storage implementation.
	 *
	 * @return Acme\CloudStorage\S3
	 */
	protected $cloud;

	/** 
	 * Create new CloudStorage class.
	 * 
	 * @param CloudStorageInterface $cloud 
	 */
	public function __construct( CloudStorageInterface $cloud )
	{
		$this->cloud = $cloud;
	}

	/**
	 * Fetch an items from the cloud.
	 * 
	 * @param  string  $key    The filename to save the file as when it reaches S3.
	 * @param  boolean $bucket (Optional) The name of the bucket we want to put the file in.
	 * @return [type]      [description]
	 */
	public function get( $key, $bucket = false )
	{
		return $this->cloud->get( $key , $bucket );
	}

	/**
	 * Push an item into the cloud.
	 * 
	 * @param  string  $key    The filename to save the file as when it reaches S3.
	 * @param  string  $source The filepath the local file.
	 * @param  boolean $bucket (Optional) The name of the bucket we want to put the file in.
	 * @return [type]          [description]
	 */
	public function put( $key, $source, $bucket  = false )
	{
		return $this->cloud->put( $key , $source, $bucket );
	}

 	/** 
 	 * Loop through a folder and upload all files to cloud storage.
 	 * 
 	 * @param  boolean $path [description]
 	 * @return [type]        [description]
 	 */
	public function loopLoad( $path = false )
	{
		// If no path is specified we will use our uploads path by default.
		// 
		if( ! $path ) $path = $path = app_path().'\uploads\\';

		// Fetch all files from a given folder.
		// 
		$files = \File::files( $path );

		// Loop through each file, pushing it out to the cloud storage server.
		// 
		foreach( $files as $file )
		{
			// Explode the string at the forward slash which will reveal just the filename.
			// 
			$explodeFileName = explode('/', $file);

			// Capture the filename.
			// 
			$filename = end($explodeFileName);

			// Now upload the file.
			// 
			CloudStorage::put( $filename , $file );
		}
	}
}