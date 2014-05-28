<?php namespace KevBaldwyn\Image\Providers\Fuel;

use KevBaldwyn\Image\Providers\Fuel as FuelProvider;
use KevBaldwyn\Image\Image;
use Router;
use Route;

class ImageServiceProvider {

	private $image;

	public function __construct(Image $image)
	{
		$this->image = $image;
	}

	/**
	 * can be used in the app_created Event to initialse the route
	 */
	public function register()
	{
		$image = $this->image;
		$route = $this->image->getProvider()->getRouteName();

		Router::add($route, new Route($route, function() use ($image) {
			$image->serve();
		}));
	}

}