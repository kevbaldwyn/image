<?php namespace KevBaldwyn\Image\SaveHandlers;

interface SaveHandlerInterface {

	public function getPublicPath();

	public function exists($filename);

	public function save($filename, array $data);

}