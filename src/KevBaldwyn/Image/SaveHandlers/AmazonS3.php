<?php namespace KevBaldwyn\Image\SaveHandlers;

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Guzzle\Http\EntityBody;
use KevBaldwyn\Image\Image;
use KevBaldwyn\Image\Providers\ProviderInterface;

class AmazonS3 implements SaveHandlerInterface {

	private $client;
	private $bucket;
	private $basePath;


	public function __construct(S3Client $client, $bucket, $basePath)
	{
		$this->client = $client;
		$this->client->registerStreamWrapper();

		$this->bucket   = $bucket;
		$this->basePath = trim($basePath, '/');
	}


	public function getPublicPath()
	{
		return 'https://' . $this->bucket . '.s3.amazonaws.com/' . $this->basePath . '/';
	}


	public function exists($filename)
	{
		$filePath = 's3://' . $this->bucket . '/'. $this->basePath . '/' . $filename;
		return file_exists($filePath);
	}


	public function save($filename, array $data)
	{
		$filename = str_replace($this->getPublicPath(), '', $filename);
		$this->client->putObject(array(
		    'Bucket' => $this->bucket,
		    'ACL'    => 'public-read',
		    'Key'    => $this->basePath . '/' . $filename,
		    'Body'   => EntityBody::factory($data['data']),
		    'ContentType' => $data['mime']
		));
	}


	public function registerCallbacks(Image $image, ProviderInterface $provider)
	{
		// remove public path (http:// part) from image path
		$image->addCallback(Image::CALLBACK_MODIFY_IMG_PATH, function($imgPath) use ($provider){
			return str_replace($provider->publicPath(), '', $imgPath);
		});

		// add the public path if the file being transformed does not (yet) exist on s3 - ie if transforming a local image to be cached on s3
		$image->addCallback(Image::CALLBACK_MODIFY_IMG_SRC_PATH, function($path) use ($provider) {
			if(!preg_match('/\.amazonaws\./', $path)) {
				return $provider->publicPath() . $path;
			}
			return $path;
		});
	}

}