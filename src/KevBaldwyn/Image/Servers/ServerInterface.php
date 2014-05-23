<?php namespace KevBaldwyn\Image\Servers;

interface ServerInterface {

	public function isFromCache();

	public function getImageData();

	public function serve();

}
