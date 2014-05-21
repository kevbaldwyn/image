<?php namespace KevBaldwyn\Image\Providers;

interface ProviderInterface {

	public function getVarResponsiveFlag();

	public function getVarImage();

	public function getVarTransform();

	public function getQueryStringData($key);

	public function getJsPath();

	public function getWorkerName();

	public function basePath();

	public function publicPath();

	public function getFromCache($checksum);

	public function putToCache($checksum, $cacheData, $cacheLifetime);

	public function fireEvent($name, array $args);
}