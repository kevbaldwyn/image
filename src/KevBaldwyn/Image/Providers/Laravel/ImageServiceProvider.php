<?php namespace KevBaldwyn\Image\Providers\Laravel;

use Config;
use Illuminate\Support\ServiceProvider;
use KevBaldwyn\Image\Providers\Laravel\Provider as LaravelProvider;
use KevBaldwyn\Image\Image;
use KevBaldwyn\Image\Providers\Laravel\Commands\MoveAssetCommand;
use KevBaldwyn\Image\Cache\ProviderCacher;

class ImageServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	public function boot() {
		
		include(__DIR__.'/routes.php');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		
		Config::package('kevbaldwyn/image', __DIR__.'/../../../../../config');

		$this->registerCache();
		$this->registerImageFileSaveHandler();
		$this->registerImage();

		$this->registerCommands();

	}


	private function registerCache() {

		$this->app->bind('kevbaldwyn.image.cache', function() {
            // default cache is file
            // trying to keep image cache separate from other cache
            $config = array();
            $config['config']['cache.driver'] = 'file';
            $config['config']['cache.path'] = storage_path() . '/cache/' . Config::get('image::cache.path');
            $config['files'] = new \Illuminate\Filesystem\Filesystem;
            return new \Illuminate\Cache\CacheManager($config);
		});

	}


	private function registerImageFileSaveHandler()
	{
		$app = $this->app;
		$this->app->bind('kevbaldwyn.image.saveHandler', function() use ($app) {
			//return new S3Handler();
			return new FileSystem($provider, '');
		});
	}


	private function registerImage() {

		$app = $this->app;

		$this->app->bind('kevbaldwyn.image', function() use ($app) {
			$provider = new LaravelProvider($app['kevbaldwyn.image.cache']);
			// option 1
			$cacher   = new ProviderCacher($provider);
			// option 2
			// $cacher   = new ImageFileCacher($app['kevbaldwyn.image.saveHandler']);
			return new Image($provider,
							 Config::get('image::cache.lifetime'),
							 Config::get('image::route'),
							 $cacher);
		});

	}


	private function registerCommands() {

		$this->app['command.kevbaldwyn.image.moveasset'] = $this->app->share(function($app) {
			return new MoveAssetCommand();
		});
				
		$this->commands('command.kevbaldwyn.image.moveasset');
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