<?php namespace KevBaldwyn\Image\Cache;

use KevBaldwyn\Image\Providers\ProviderInterface;
use KevBaldwyn\Image\Image;

class ProviderCacher implements CacherInterface {

	protected $provider;
	protected $imgPath;
	protected $operations;
	protected $cacheLifetime;


	public function __construct(ProviderInterface $provider)
	{
		$this->provider = $provider;
	}


	public function getSrcPath()
	{
		return $this->publicPath . $this->imgPath;
	}


	public function init($imgPath, $operations, $cacheLifetime, $publicPath)
	{
		$this->imgPath       = $imgPath;
		$this->operations    = $operations;
		$this->cacheLifetime = $cacheLifetime;
		$this->publicPath    = $publicPath;
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
