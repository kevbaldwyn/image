<?php

$route = rtrim(\Config::get('image::route'), '/');
Route::get($route, function() {
	Image::serve();
});