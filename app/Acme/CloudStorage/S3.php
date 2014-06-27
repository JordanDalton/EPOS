<?php namespace Acme\CloudStorage;

use Acme\Interfaces\CloudStorage\CloudStorageInterface;
use App, Config, File;

class S3 implements CloudStorageInterface {

	/** 
	 * Client to interact with Amazon Simple Storage Service.
	 * 
	 * @return Aws\S3\S3Client
	 */
	protected $connection;

	/**
	 * Create new S3 instance.
	 */
	public function __construct()
	{
		$this->connection = $s3 = App::make('aws')->get('s3');
	}

	/**
	 * Retrieves objects from Amazon S3.
	 * 
	 * @param  string  $key    The filename to save the file as when it reaches S3.
	 * @param  boolean $bucket (Optional) The name of the bucket we want to put the file in.
	 * @return [type]      [description]
	 */
	public function get( $key, $bucket )
	{
		// Determine which bucket the file will be uploaded to.
		// 
		$bucket = $bucket ? $bucket : Config::get('aws::config.buckets.default');

		// Fetch file from S3.
		// 
		return $this->connection->getObject(array(
		    'Bucket'     => $bucket,
		    'Key'        => $key
		));
	}

	/** 
	 * Adds an object to a bucket.
	 * 
	 * @param  string  $key    The filename to save the file as when it reaches S3.
	 * @param  string  $source The filepath the local file.
	 * @param  boolean $bucket (Optional) The name of the bucket we want to put the file in.
	 * @return [type]          [description]
	 */
	public function put( $key, $source, $bucket  = false )
	{
		// Determine which bucket the file will be uploaded to.
		// 
		$bucket = $bucket ? $bucket : Config::get('aws::config.buckets.default');

		// Push the file onto S3 storage.
		// 
		$putObject = $this->connection->putObject(array(
		    'Bucket'     => $bucket,
		    'Key'        => $key,
		    'SourceFile' => $source,
		));

		// If the file was successfully moved to the cloud we will need
		// to remove the local copy.
		// 
		if( $putObject['ObjectURL'] ) File::delete($source);
	}
}