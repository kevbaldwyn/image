<?php namespace KevBaldwyn\Image\Servers;

/**
 * Cache server
 * provides implementation for getting image data from the cache
 */
class Cache implements ServerInterface {

	private $cacheData;


	public function __construct(array $cacheData) 
	{
		$this->cacheData = $cacheData;
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
		return $this->cacheData;
	}


	/**
	 * output the image based on the data in the cache
	 * @return string image / headers, kills execution
	 */
	public function serve()
	{
		if (($string = $this->cacheData['data']) && ($mimetype = $this->cacheData['mime'])) {
			header('Content-Type: '.$mimetype);
			die($string);
		}else{
			throw new \Exception('There was an error with the image cache');
		}
	}

}