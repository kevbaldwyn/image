<?php namespace KevBaldwyn\Image\Servers;

use KevBaldwyn\Image\Cache\CacherInterface;

/**
 * Cache server
 * provides implementation for getting image data from the cache
 */
class Cache implements ServerInterface {

	private $cacher;


	public function __construct(CacherInterface $cacher) 
	{
		$this->cacher = $cacher;
	}


	/**
	 * always return true as this is the cache!
	 * @return boolean true
	 */
	public function isFromCache() 
	{
		return true;
	}


	/**
	 * get the cached mime type and image data
	 * @return array ['mime' => string, 'data' => string]
	 */
	public function getImageData()
	{
		return $this->cacher->getImageData();
	}


	/**
	 * output the image based on the data in the cache
	 * @return string image / headers, kills execution
	 */
	public function serve()
	{
		$this->cacher->serve();
	}

}