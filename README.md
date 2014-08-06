# Image
A Laravel 4 wrapper for the Imagecow image resizing / respoisive image package. See [https://github.com/oscarotero/imageCow][1] for more detail on the underlying image manipulation package.

Image cow can use either GD or ImageMagick to transform image.


## Instalation
Install as any other Laravel 4 package:

1) Add to composer:

    "require": {
        ...
        "kevbaldwyn/image":"dev-master"
        ...
    }

2) Composer Update:

    $ composer update

3) Add to the providers array in app.php:

    	'providers' => array(

	    ...

	    'KevBaldwyn\Image\Providers\Laravel\ImageServiceProvider'
	)

4) Add to the facades array in app.php:

    	'aliases' => array(

	    ...

	    'Image' => 'KevBaldwyn\Image\Providers\Laravel\Facades\Image'
	)

5) Publish the package config file to change the defaults:

    $ php artisan config:publish kevbaldwyn/image

6) Copy the /vendor/imagecow/imagecow/Imagecow/Imagecow.js file to a publicly accessible web directory. The default path is set as /public/js/Imagecow.js, but whatever it is set as in the config the file must exist.

## Usage
### Standard
To provide image links on your templates use like so:

    <img src="{{ Image::path('/image.jpg', 'resizeCrop', 400, 200) }}" />

Where the first argument is the image which is referenced from the root of the public directory. The second argument is the transform method and each subsequent argument is an argument that would be passed to the relevant transform method used. See the Imagecow Documentation for more details.

### Responsive
To provide links to responsive images use a similar syntax:

    <img src="{{ Image::path('/image.jpg', 'resizeCrop', 400, 200)->responsive('max-width=400', 'resize', 100) }}" />

The first argument is the "rule" and the subsequent arguments are the transform conditions to apply to that rule, following the same format. You can apply multiple responsive breakpoints by calling responsive multiple times.

### Caching
All images are cached automatically, they are cached to the filesystem in the storage directory, the exact path and lifetime of the cache are configurable in the package config.


  [1]: https://github.com/oscarotero/imageCow