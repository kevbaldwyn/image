<?php namespace KevBaldwyn\Image\Providers;

use Config;
use Illuminate\Cache\CacheManager;

class LaravelProvider implements ProviderInterface {

	private $cache;

	public function __construct(CacheManager $cache)
	{
		$this->cache = $cache;
	}

	public function getVarResponsiveFlag()
	{
		return Config::get('image::vars.responsive_flag');
	}

	public function getVarImage()
	{
		return Config::get('image::vars.image');
	}

	public function getVarTransform()
	{
		return Config::get('image::vars.transform');
	}

	public function getJsPath()
	{
		return Config::get('image::js_path');
	}

	public function getWorkerName()
	{
		return Config::get('image::worker');
	}

	public function basePath()
	{
		return base_path();
	}

	public function publicPath()
	{
		return public_path();
	}

	public function getFromCache($checksum)
	{
		$this->cache->get($checksum);
	}

	public function putToCache($checksum, $cacheData, $cacheLifetime)
	{
		$this->cache->put($checksum, $cacheData, $cacheLifetime);
	}

}