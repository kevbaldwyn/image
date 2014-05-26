<?php namespace KevBaldwyn\Image\SaveHandlers;

use KevBaldwyn\Image\Providers\ProviderInterface;

class FileSystem implements SaveHandlerInterface {

	private $basePath;
	private $dir;


	public function __construct(ProviderInterface $provider, $dir)
	{
		$this->basePath = $provider->publicPath();
		$this->dir      = $dir;
	}


	public function getPublicPath()
	{
		return $this->dir;
	}


	public function exists($filename)
	{
		$filePath = $this->basePath . $this->getPublicPath() . $filename;
		return file_exists($filePath);
	}


	public function save($filename, array $data)
	{
		$path = $this->basePath . $this->dir;
		if(!is_dir($path)) {
			mkdir($path);
		}
		file_put_contents($path . $filename, $data['data']);
	}

}