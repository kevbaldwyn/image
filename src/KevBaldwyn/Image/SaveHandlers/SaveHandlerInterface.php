<?php namespace KevBaldwyn\Image\SaveHandlers;

interface SaveHandlerInterface {

	public function getPublicPath();

	public function save($filename, array $data);

}