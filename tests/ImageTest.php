<?php

use \KevBaldwyn\Image\Image;
use \KevBaldwyn\Image\Providers\ProviderInterface;
use \Mockery as m;

class ImageTest extends \PHPUnit_Framework_TestCase {

	public function testCallBacksModifyBasePath()
	{
		$image = new Image(static::mockProvider(), 100, '/image-server');
		$image->addCallback(Image::CALLBACK_MODIFY_PATH, function($path) {
			return '/prepend' . $path;
		});
		$image->addCallback(Image::CALLBACK_MODIFY_PATH, function($path) {
			return $path . '/append';
		});

		$this->assertSame('/prepend/image-server/append?', $image->getBasePath());
	}


	public static function mockProvider()
	{
		$provider = m::mock('\KevBaldwyn\Image\Providers\ProviderInterface');
		return $provider;
	}

}