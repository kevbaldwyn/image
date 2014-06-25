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
	private $srcPath;
	private $savePath;


	public function __construct(S3Client $client, $bucket, $basePath)
	{
		$this->client = $client;
		$this->client->registerStreamWrapper();

		$this->bucket   = $bucket;
		$this->basePath = trim($basePath, '/');
	}


	public function setPaths($imgPath, $publicPath)
	{
		// if on s3
		if(preg_match('/\.amazonaws\./', $imgPath)) {
			$savePath = str_replace($this->getPublicPath(), '', $imgPath);
		// if remote somewhere else
		}elseif(preg_match('/^https?\:\/\//', $imgPath)) {
			$url      = parse_url($imgPath);
			$savePath = trim($url['path'], '/');
		// if local
		}else{
			$savePath = $imgPath;
			$imgPath  = $publicPath . $imgPath;
		}

		$this->srcPath  = $imgPath;
		$this->savePath = dirname(trim($savePath, '/')) . '/';
	}


	public function getPublicPath()
	{
		return 'https://' . $this->bucket . '.s3.amazonaws.com/' . $this->basePath . '/';
	}


	public function getPublicServePath()
	{
		return $this->getPublicPath() . $this->getSavePath();
	}


	public function getSrcPath()
	{
		return $this->srcPath;
	}


	public function getSavePath()
	{
		return $this->savePath;
	}


	public function exists($filename)
	{
		$filePath = 's3://' . $this->bucket . '/'. $this->basePath . '/' . $this->savePath . $filename;
		return file_exists($filePath);
	}


	public function save($filename, array $data)
	{
		$this->client->putObject(array(
		    'Bucket' => $this->bucket,
		    'ACL'    => 'public-read',
		    'Key'    => $this->basePath . '/' . $this->savePath . $filename,
		    'Body'   => EntityBody::factory($data['data']),
		    'ContentType' => $data['mime']
		));
	}

}