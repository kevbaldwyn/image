<?php namespace KevBaldwyn\Image;

use Config;
use Illuminate\Support\ServiceProvider;

class ImageServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		
		Config::package('kevbaldwyn/image', __DIR__.'/../../config');

		$app = $this->app;

		$this->app->bind('kevbaldwyn.image.worker', function() {
			return \Imagecow\Image::create(Config::get('image::worker'));
		});

		$this->app->bind('kevbaldwyn.image', function() use ($app) {
			return new \KevBaldwyn\Image\Image($app['kevbaldwyn.image.worker']);
		});

		include(__DIR__.'/routes.php');

	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('kevbaldwyn.image');
	}

}