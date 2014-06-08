<?php namespace KevBaldwyn\Image\SaveHandlers;

use KevBaldwyn\Image\Image;
use KevBaldwyn\Image\Providers\ProviderInterface;

class FileSystem implements SaveHandlerInterface {

	private $basePath;

	public function __construct(ProviderInterface $provider)
	{
		$this->basePath = $provider->publicPath();
	}


	public function setPaths($imgPath, $publicPath)
	{
		$this->srcPath = $publicPath . $imgPath;
		$this->savePath = dirname($this->srcPath) . '/';
	}


	public function getPublicPath()
	{
		return $this->basePath;
	}


	public function getPublicServePath()
	{
		return str_replace($this->basePath, '', $this->savePath);
	}


	public function getSrcPath()
	{
		return $this->srcPath;
	}


	public function getSavePath()
	{
		return $this->savePath;
	}


	public function exists($filename)
	{
		$filePath = $this->savePath . $filename;
		return file_exists($filePath);
	}


	public function save($filename, array $data)
	{
		$path = $this->savePath;
		if(!is_dir($path)) {
			mkdir($path, true);
		}
		file_put_contents($path . $filename, $data['data']);
	}

}