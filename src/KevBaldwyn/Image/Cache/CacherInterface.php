<?php namespace KevBaldwyn\Image\Cache;

use KevBaldwyn\Image\Providers\ProviderInterface;

interface CacherInterface {

	public function init($imgPath, $operations, $cacheLifetime);

	public function exists();

	public function serve();

	public function put();
}