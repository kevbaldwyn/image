<?php

return array(

	/**
	 * the image worker, must be "Gd" or "Imagick"
	 */
	'worker' => 'Gd',

	'route' => '/_img',

	/**
	 * note this is the server path to the file 
	 * from base_path()
	 */
	'js_path' => '/public/js/Imagecow.js',

	/**
	 * path to placeholder image if no image found
	 */
	'placeholder' => '/images/thumb.gif',
	
	/**
	 * various $_GET variables
	 */
	'vars' => array(
		'image'           => 'img',
		'responsive_flag' => 'responsive',
		'transform'       => 'transform'
	),

	'cache' => array(
		'lifetime' => 1,
		'path'     => 'images' // /app/storage/cache/{images}
	)
 
);
