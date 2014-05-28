<?php namespace KevBaldwyn\Image\Providers\Fuel;

use KevBaldwyn\Image\Providers\ProviderInterface;
use Config;
use Arr;
use Cache;
use Event;
use Input;

class Provider implements ProviderInterface {

	private $config;

	public function __construct(array $config)
	{
		$this->config = $config;
	}

	public function getVarResponsiveFlag()
	{
		return Arr::get('vars.responsive_flag', $this->config);
	}

	public function getVarImage()
	{
		return Arr::get('vars.image', $this->config);
	}

	public function getVarTransform()
	{
		return Arr::get('vars.transform', $this->config);
	}

	public function getQueryStringData($key)
	{
		return Input::get($key);
	}

	public function getJsPath()
	{
		return Arr::get('js_path', $this->config);
	}

	public function getWorkerName()
	{
		return Arr::get('worker', $this->config);
	}

	public function basePath()
	{
		return APPPATH;
	}

	public function publicPath()
	{
		return DOCROOT;
	}

	public function getFromCache($checksum)
	{
		Cache::get($checksum);
	}

	public function putToCache($checksum, $cacheData, $cacheLifetime)
	{
		Cache::set($checksum, $cacheData, $cacheLifetime);
	}

	/**
	 * a simple wrapper for firing a fuel event
	 * @param  string $name event name
	 * @param  array  $args args
	 * @return void
	 */
	public function fireEvent($name, array $args)
	{
		Event::trigger($name, $args);
	}

}