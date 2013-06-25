<?php

return array(

	/**
	 * the image worker (GD / Immagick)
	 */
	'worker' => '',

	/**
	 * note this is the server path to the file 
	 * from base_path()
	 */
	'js_path' => '/public/js/Imagecow.js',

	/**
	 * various $_GET variables
	 */
	'vars' => array(
		'image'           => 'img',
		'responsive_flag' => 'responsive',
		'transform'       => 'transform'
	)
 
);