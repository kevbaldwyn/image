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


	public function testPathOptions()
	{
		$image = new Image(static::mockProvider(), 100, '/image-server');
		// resize
		$this->assertSame(
			'/image-server?img=/path/to/image.jpg&transform=resize,400,200', 
			$image->path('/path/to/image.jpg', 'resize', 400, 200)->__toString()
		);
		$this->assertSame(
			'/image-server?img=/path/to/image.jpg&transform=resize,400,200,1', 
			$image->path('/path/to/image.jpg', 'resize', 400, 200, 1)->__toString()
		);
		$this->assertSame(
			'/image-server?img=/path/to/image.jpg&transform=resize,400', 
			$image->path('/path/to/image.jpg', 'resize', 400)->__toString()
		);

		// resizeCrop
		$this->assertSame(
			'/image-server?img=/path/to/image.jpg&transform=resizeCrop,400,200', 
			$image->path('/path/to/image.jpg', 'resizeCrop', 400, 200)->__toString()
		);
		$this->assertSame(
			'/image-server?img=/path/to/image.jpg&transform=resizeCrop,400,200,center,middle', 
			$image->path('/path/to/image.jpg', 'resizeCrop', 400, 200, 'center', 'middle')->__toString()
		);

		// crop
		$this->assertSame(
			'/image-server?img=/path/to/image.jpg&transform=crop,400,200', 
			$image->path('/path/to/image.jpg', 'crop', 400, 200)->__toString()
		);
		$this->assertSame(
			'/image-server?img=/path/to/image.jpg&transform=crop,400,200,left,top', 
			$image->path('/path/to/image.jpg', 'crop', 400, 200, 'left', 'top')->__toString()
		);
		$this->assertSame(
			'/image-server?img=/path/to/image.jpg&transform=crop,400,200,20,50%', 
			$image->path('/path/to/image.jpg', 'crop', 400, 200, 20, '50%')->__toString()
		);
		$this->assertSame(
			'/image-server?img=/path/to/image.jpg&transform=crop,50%,50%', 
			$image->path('/path/to/image.jpg', 'crop', '50%', '50%')->__toString()
		);
	}


	public function testResponsivePathOptions()
	{
		$image = new Image(static::mockProvider(), 100, '/image-server');
		$this->assertSame(
			'/image-server?img=/path/to/image.jpg&transform=resizeCrop,800,600;max-width=400:resize,400&responsive=true', 
			$image->path('/path/to/image.jpg', 'resizeCrop', 800, 600)
				->responsive('max-width=400', 'resize', '400')
				->__toString()
		);
	}


	public static function mockProvider()
	{
		$provider = m::mock('\KevBaldwyn\Image\Providers\ProviderInterface');
		$provider->shouldReceive('getVarImage')->andReturn('img');
		$provider->shouldReceive('getVarTransform')->andReturn('transform');
		$provider->shouldReceive('getVarResponsiveFlag')->andReturn('responsive');

		return $provider;
	}

}