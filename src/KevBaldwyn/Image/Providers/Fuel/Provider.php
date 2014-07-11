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
		return Arr::get($this->config, 'vars.responsive_flag');
	}

	public function getVarImage()
	{
		return Arr::get($this->config, 'vars.image');
	}

	public function getVarTransform()
	{
		return Arr::get($this->config, 'vars.transform');
	}

	public function getQueryStringData($key)
	{
		return Input::get($key);
	}

	public function getJsPath()
	{
		return Arr::get($this->config, 'js_path');
	}

	public function getWorkerName()
	{
		return Arr::get($this->config, 'worker');
	}

	public function basePath()
	{
		return DOCROOT . '/..';
	}

	public function publicPath()
	{
		return rtrim(DOCROOT, '/');
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


	/**
	 * --- Fuel only methods
	 */
	
	public function getRouteName()
	{
		return Arr::get($this->config, 'route');
	}

}