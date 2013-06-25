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

		$this->app->bind('kevbaldwyn.image.cache', function() use ($app) {
            // default cache is file
            // trying to keep image cache separate from other cache
            $config = array();
            $config['config']['cache.driver'] = 'file';
            $config['config']['cache.path'] = storage_path() . '/cache/images';
            $config['files'] = new \Illuminate\Filesystem\Filesystem;
            return new \Illuminate\Cache\CacheManager($config);
		});

		$this->app->bind('kevbaldwyn.image', function() use ($app) {
			return new \KevBaldwyn\Image\Image($app['kevbaldwyn.image.worker'], 
											   $app['kevbaldwyn.image.cache'],
											   Config::get('image::cache.lifetime'));
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