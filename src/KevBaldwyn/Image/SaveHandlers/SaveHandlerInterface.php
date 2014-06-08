<?php namespace KevBaldwyn\Image\SaveHandlers;

use KevBaldwyn\Image\Image;
use KevBaldwyn\Image\Providers\ProviderInterface;

interface SaveHandlerInterface {

	public function getPublicPath();

	public function exists($filename);

	public function save($filename, array $data);

	public function getSrcPath();

	public function setPaths($imgPath, $publicPath);
}