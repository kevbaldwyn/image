<?php

use \KevBaldwyn\Image\SaveHandlers\FileSystem;
use \KevBaldwyn\Image\Providers\ProviderInterface;
use \Mockery as m;

class FileSystemTest extends \PHPUnit_Framework_TestCase {

	public function testSave()
	{
		$fileName = 'createdfile-' . time() . '.jpg';
		$basePath = __DIR__ . '/../';
		$data = array(
			'mime' => 'image/jpeg',
			'data' => file_get_contents($basePath . 'assets/image.jpg')
		);
		$provider = m::mock('\KevBaldwyn\Image\Providers\ProviderInterface');
		$provider->shouldReceive('publicPath')->andReturn($basePath);

		$fileSystem = new FileSystem($provider, 'assets/upload/');
		$fileSystem->save($fileName, $data);

		$this->assertTrue(file_exists($basePath . 'assets/upload/' . $fileName));
		unlink($basePath . 'assets/upload/' . $fileName);
	}


	public function testExists()
	{
		$basePath = __DIR__ . '/../';
		$filename = 'existingfile.jpg';
		
		$provider = m::mock('\KevBaldwyn\Image\Providers\ProviderInterface');
		$provider->shouldReceive('publicPath')->andReturn($basePath);

		$fileSystem = new FileSystem($provider, 'assets/upload/');

		if(!is_dir($basePath . 'assets/upload/')) {
			mkdir($basePath . 'assets/upload/');
		}
		// create and check api finds it
		file_put_contents($basePath . 'assets/upload/' . $filename, file_get_contents($basePath . 'assets/image.jpg'));
		$this->assertTrue($fileSystem->exists($filename));

		// delete file and check again
		unlink($basePath . 'assets/upload/existingfile.jpg');
		$this->assertFalse($fileSystem->exists($filename));
	}

}