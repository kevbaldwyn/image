<?php namespace KevBaldwyn\Image\Cache;

use KevBaldwyn\Image\Providers\ProviderInterface;

class ProviderCacher implements CacherInterface {

	protected $provider;
	protected $imgPath;
	protected $operations;
	protected $cacheLifetime;


	public function __construct(ProviderInterface $provider)
	{
		$this->provider = $provider;
	}


	public function init($imgPath, $operations, $cacheLifetime)
	{
		$this->imgPath       = $imgPath;
		$this->operations    = $operations;
		$this->cacheLifetime = $cacheLifetime;
	}


	public function exists()
	{
		$this->checksum  = md5($this->imgPath . ';' . serialize($this->operations));
		$this->cacheData = $this->provider->getFromCache($this->checksum);
		return $this->cacheData;
	}


	public function serve()
	{
		if (($string = $this->cacheData['data']) && ($mimetype = $this->cacheData['mime'])) {
			header('Content-Type: '.$mimetype);
			die($string);
		}else{
			throw new \Exception('There was an error with the image cache');
		}
	}


	public function put($cacheData)
	{
		$this->provider->putToCache($this->checksum, $cacheData, $this->cacheLifetime);
	}

}