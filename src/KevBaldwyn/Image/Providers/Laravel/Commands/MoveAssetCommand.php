<?php namespace KevBaldwyn\Image\Providers\Laravel\Commands;

use Config;
use Illuminate\Console\Command;

class MoveAssetCommand extends Command {


	protected $name = 'kevbaldwyn:image:moveasset';
	protected $description = 'Move required assets to public path';

	
	public function fire() {
		
		$jsFile = Config::get('image::js_path');
		$oldPath = base_path() . '/vendor/imagecow/imagecow/Imagecow/Imagecow.js';
		$newPath = base_path() . $jsFile;
		$this->info('Moving ' . $oldPath . ' to ' . $newPath);

		if(!copy($oldPath, $newPath)) {
			$this->error('Unable to move file, please move it manually.');
		}

	}

	protected function getArguments() {
		return array();
	}

	protected function getOptions() {
		return array();
	}

}