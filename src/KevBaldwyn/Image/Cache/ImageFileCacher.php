<?php namespace KevBaldwyn\Image\Cache;

use KevBaldwyn\Image\SaveHandlers\SaveHandlerInterface;
use KevBaldwyn\Image\Image;

class ImageFileCacher implements CacherInterface {

	protected $imgPath;
	protected $operations;
	protected $cacheLifetime;
	protected $saveHandler;


	public function __construct(SaveHandlerInterface $saveHandler)
	{
		$this->saveHandler = $saveHandler;
	}


	public function init($imgPath, $operations, $cacheLifetime)
	{
		$this->imgPath       = $imgPath;
		$this->operations    = $operations;
		$this->cacheLifetime = $cacheLifetime;
	}


	public function register(Image $image)
	{
		$image->addCallback(Image::CALLBACK_MODIFY_IMG_PATH, function($imgPath) use ($provider){
			return str_replace($provider->publicPath(), '', $imgPath);
		});
	}


	public function exists()
	{
		$file = $this->getFilename();
		return $this->saveHandler->exists($file);
	}


	public function serve()
	{
		// 301 to file / url
		header('Location: ', $this->saveHandler->getPublicPath() . $this->getFilename());
		die();
	}


	public function put($data)
	{
		// save handler - s3, filesystem etc
		$filename = $this->getFilename();
		$this->saveHandler->save($filename, $data);
	}


	public function getFilename()
	{
		// transform $imgPath + $operations into a unique filename
		$file = basename($this->imgPath);
		$dir  = dirname($this->imgPath);

		$ops = str_replace(array('&', ':', ';', '?', '.', ','), '-', $this->operations);
		return trim($dir . '/' . $ops . '-' . $file, './');
	}

}