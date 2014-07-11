<?php namespace KevBaldwyn\Image\Servers;

use KevBaldwyn\Image\Cache\CacherInterface;

/**
 * ImageCow Server
 * provides implementation for getting image data from image cow
 */
class ImageCow implements ServerInterface {

	private $worker;
	private $operations;
	private $cacher;
	private $data;


	public function __construct($worker, $operations, CacherInterface $cacher)
	{
		$this->worker        = $worker;
		$this->operations    = $operations;
		$this->cacher        = $cacher;
	}


	/**
	 * get the worker currently doing the transformations
	 * @return Imagecow\Image
	 */
	public function getWorker()
	{
		return $this->worker;
	}


	/**
	 * do the transformation and save the result to the cache
	 * @return void
	 */
	public function create()
	{
		$this->worker->transform($this->operations);
			
		$this->data = array('mime' => $this->worker->getMimeType(),
						   	'data' => $this->worker->getString());

		$this->cacher->put($this->data);
	}


	/**
	 * we are never getting the image from the cache if we are performing a transformation
	 * @return boolean false
	 */
	public function isFromCache()
	{
		return false;
	}


	/**
	 * get an array of mime type and data for the new image
	 * @return array ['mime' => string, 'data' => string]
	 */
	public function getImageData()
	{
		$this->create();
		return $this->data;
	}


	/**
	 * output the image
	 * @return string the image data / correct headers, kills the script execution
	 */
	public function serve()
	{
		$this->worker->show();
	}

}