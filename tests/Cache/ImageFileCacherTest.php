<?php

use \KevBaldwyn\Image\Cache\ImageFileCacher;
use \Mockery as m;

class ImageFileCacherTest extends \PHPUnit_Framework_TestCase {

	public function testCreateFileName()
	{
		$saveHandler = m::mock('\KevBaldwyn\Image\SaveHandlers\SaveHandlerInterface');
		$saveHandler->shouldReceive('setPaths');
		$operations = 'resizeCrop,400,200,center,middle';
		
		$cacher = new ImageFileCacher($saveHandler);
		$cacher->init('path/to/image.jpg', $operations, 100, '');
		$this->assertSame('resizeCrop-400-200-center-middle-image.jpg', $cacher->getFilename());

		$cacher->init('image.jpg', $operations, 100, '');
		$this->assertSame('resizeCrop-400-200-center-middle-image.jpg', $cacher->getFilename());
	}

}