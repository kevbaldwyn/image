<?php namespace KevBaldwyn\Image\Providers\Laravel;

use KevBaldwyn\Image\Providers\ProviderInterface;
use Config;
use Illuminate\Cache\CacheManager;
use Event;
use Input;

class Provider implements ProviderInterface {

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

	public function getQueryStringData($key)
	{
		return Input::get($key);
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

	/**
	 * a simple wrapper for firing a laravel event
	 * @param  string $name event name
	 * @param  array  $args args
	 * @return void
	 */
	public function fireEvent($name, array $args)
	{
		Event::fire($name, $args);
	}

}