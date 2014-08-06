<?php namespace KevBaldwyn\Image\Providers\Fuel;

use KevBaldwyn\Image\Providers\Fuel as FuelProvider;
use KevBaldwyn\Image\Image;
use Router;
use Route;
use Closure;

class ImageServiceProvider {

	private $image;

	public function __construct(Image $image)
	{
		$this->image = $image;
	}

	/**
	 * can be used in the app_created Event to initialse the route
	 */
	public function register(Closure $headerCallback = null)
	{
		$image = $this->image;
		$route = trim($this->image->getProvider()->getRouteName(), '/');

		Router::add($route, new Route($route, function() use ($image, $headerCallback) {
			if(!is_null($headerCallback)) {
				$headerCallback($image);
			}
			$image->serve();
		}));
	}

}