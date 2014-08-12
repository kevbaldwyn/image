<?php namespace KevBaldwyn\Image\Cache;

use KevBaldwyn\Image\SaveHandlers\SaveHandlerInterface;
use KevBaldwyn\Image\Image;

class ImageFileCacher implements CacherInterface {

	protected $srcPath;
	protected $operations;
	protected $cacheLifetime;
	protected $saveHandler;


	public function __construct(SaveHandlerInterface $saveHandler)
	{
		$this->saveHandler = $saveHandler;
	}


	public function init($imgPath, $operations, $cacheLifetime, $publicPath)
	{
		$this->srcPath       = $imgPath;
		$this->operations    = $operations;
		$this->cacheLifetime = $cacheLifetime;

		$this->saveHandler->setPaths($this->srcPath, $publicPath);
	}


	public function exists()
	{
		$file = $this->getFilename();
		return $this->saveHandler->exists($file);
	}


	public function getSrcPath()
	{
		return $this->saveHandler->getSrcPath();
	}


	public function getSavePath()
	{
		return $this->saveHandler->getSavePath();
	}


	public function serve()
	{
		// which is faster?
		// 301 to file / url
		header('HTTP/1.1 301');
		header('Location: ' . $this->saveHandler->getPublicServePath() . $this->getFilename());
		die();
		
		// read file and output
		/*
		$file = $this->saveHandler->getPublicServePath() . $this->getFilename();
		$mimetype = exif_imagetype($file);
		header('Content-Type: '.$mimetype);
		readfile($file);
		die();
		*/
	}


	public function put($data)
	{
		// save handler - s3, filesystem etc
		$filename = $this->getFilename();
		$this->saveHandler->save($filename, $data);
	}


	public function getFilename()
	{
		// transform $srcPath + $operations into a unique filename
		$file = basename($this->srcPath);

		$ops = str_replace(array('&', ':', ';', '?', '.', ','), '-', $this->operations);
		return trim($ops . '-' . $file, './');
	}

}