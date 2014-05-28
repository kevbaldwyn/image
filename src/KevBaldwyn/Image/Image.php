<?php namespace KevBaldwyn\Image;

use KevBaldwyn\Image\Providers\ProviderInterface;
use KevBaldwyn\Image\Servers\Cache as CacheServer;
use KevBaldwyn\Image\Cache\CacherInterface;
use KevBaldwyn\Image\Servers\ImageCow as ImageCowServer;
use Imagecow\Image as ImageCow;
use Closure;

class Image {

	private $provider;
	private $cacher;
	private $cacheLifetime; // minutes

	private $pathStringbase = '';
	private $pathString;

	private $callbacks = array();

	private $server;

	/**
	 * some constants for strings used internally
	 */
	const EVENT_ON_CREATED = 'kevbaldwyn.image.created';
	const CALLBACK_MODIFY_IMG_PATH = 'callback.modifyImgPath';


	public function __construct(ProviderInterface $provider, $cacheLifetime, $serveRoute, CacherInterface $cacher) {
		$this->provider       = $provider;
		$this->cacher         = $cacher;
		$this->cacheLifetime  = $cacheLifetime;
		$this->pathStringBase = $serveRoute;
	}


	/**
	 * build the responsive image part of the url
	 * any number of parameters can be sent
	 * @return static $this
	 */
	public function responsive(/* any number of params */) {
		$params = func_get_args();
		if(count($params) <= 1) {
			throw new \Exception('Not enough params provided to generate a responsive image');
		}
		
		list($rule, $transform) = $this->getPathOptions($params);

		// write out the reposinsive url part
		$this->pathString .= ';' . $rule . ':' . $transform . '&' . $this->provider->getVarResponsiveFlag() . '=true';
		return $this;
	}


	/**
	 * get the path to the image
	 * callbacks can be applied to modify the path
	 * @return string path to the image to be used
	 */
	public function getImagePath()
	{
		$imgPath = $this->provider->publicPath() . $this->provider->getQueryStringData($this->provider->getVarImage());
		if(array_key_exists(self::CALLBACK_MODIFY_IMG_PATH, $this->callbacks)) {
			foreach($this->callbacks[self::CALLBACK_MODIFY_IMG_PATH] as $callback) {
				$imgPath = $callback($imgPath);
			}
		}
		return $imgPath;
	}


	/**
	 * build the initial transformed path for the image
	 * @return static $this
	 */
	public function path(/* any number of params */) {
		
		$params = func_get_args();
		if(count($params) <= 1) {
			throw new \Exception('Not enough params provided to generate an image');
		}
		
		list($img, $transform) = $this->getPathOptions($params);

		// write out the resize path
		$this->pathString = $this->getBasePath();
		$this->pathString .= $this->provider->getVarImage() . '=' . $img;
		$this->pathString .= '&' . $this->provider->getVarTransform() . '=' . $transform;
		return $this;
	}


	/**
	 * get the data for the image
	 * @return array ['mime' => string, 'data' => string]
	 */
	public function getImageData()
	{
		$server = $this->getServer();
		return $server->getImageData();
	}


	/**
	 * check if we are serving from the cache
	 * @return boolean
	 */
	public function isFromCache()
	{
		return $this->getServer()->isFromCache();
	}


	/**
	 * serve and output the new image
	 * @return image data and headers
	 */
	public function serve() {

		$server = $this->getServer();

		if(!$server->isFromCache()) {
			$server->create();

			$this->provider->fireEvent(self::EVENT_ON_CREATED, array($this->getImagePath(), $server->getWorker(), $this->getOperations()));
		}	

		$server->serve();
		
	}


	/**
	 * get the correctly instaniated server image in play
	 * takes into account cache and configured options
	 * @return KevBaldwyn\Image\Servers\ServerInterface
	 */
	private function getServer()
	{
		if(is_null($this->server)) {
			// get the tarnsformations
			$operations = $this->getOperations();
			
			// get the image path
			$imgPath   = $this->getImagePath();

			// check cache
			$this->cacher->init($imgPath, $operations, $this->cacheLifetime);

			// get the correctly instantiated server object
			if($this->cacher->exists()) {
				$this->server = new CacheServer($this->cacher);
			}else{
				$worker = Imagecow::create($imgPath, $this->provider->getWorkerName());
				$this->server = new ImageCowServer(
					$worker, 
					$operations,
					$this->cacher
				);
			}
		}

		return $this->server;
	}


	/**
	 * add a callback
	 * @param string  $type     the type of callback to be added
	 * @param Closure $callback the callback
	 */
	public function addCallback($type, Closure $callback)
	{
		$this->callbacks[$type][] = $callback;
	}


	/**
	 * output the javascript file to be used by the ImageCow responsive functionality
	 * @param  string $publicDir the path the file sits under
	 * @return string            the javascript
	 */
	public function js($publicDir = '/public') {
		
		$jsFile = $this->provider->getJsPath();

		// hacky hack hack
		// if .js file doesn't exist in defined location then copy it there?! (or throw an error?)
		if(!file_exists($this->provider->basePath() . $jsFile)) {
			throw new \Exception('Javascript file does not exists! Please copy /vendor/imagecow/imagecow/Imagecow/Imagecow.js to ' . $jsFile);
		}

		// check if the path starts with "public"
		// if so then we need to remove it 
		// - the file_exists is checking the server path not the web path
		// will this always be the case?
		//$path = (preg_match('/^\/?public\//', $jsFile)) ? str_replace('public/', '', $jsFile) : $jsFile;
		
		// nicer to pass it through as a param instead:
		$path = (!is_null($publicDir)) ? str_replace($publicDir, '', $jsFile) : $jsFile;

		$str  = '
		<script src="' . $path . '" type="text/javascript" charset="utf-8"></script>
		<script type="text/javascript">
    		Imagecow.init();
		</script>';

		return $str;

	}


	/**
	 * returns the path to the image
	 * @return string
	 */
	public function __toString() {
		return $this->pathString;
	}


	/**
	 * get the base path for the image server
	 * @return string 
	 */
	private function getBasePath()
	{
		$basePath = $this->pathStringBase;
		return $basePath . '?';
	}


	/**
	 * get the options passed to the instance for performing the transformation
	 * @return array
	 */
	private function getOperations()
	{
		if($this->provider->getQueryStringData($this->provider->getVarResponsiveFlag()) == 'true') {
			$operations = Imagecow::getResponsiveOperations($_COOKIE['Imagecow_detection'], $this->provider->getQueryStringData($this->provider->getVarTransform()));
		}else{
			$operations = $this->provider->getQueryStringData($this->provider->getVarTransform());
		}
		return $operations;
	}


	/**
	 * build the path options for the server path string
	 * @param  array $params the transform options
	 * @return array         
	 */
	private function getPathOptions($params) {

		$first = $params[0];

		foreach($params as $key => $param) {
			if($key > 0) {
				$transformA[] = $param;
			}
		}
		$transform = implode(',', $transformA);

		return array($first, $transform);

	}


	public function getProvider()
	{
		return $this->provider;
	}

}