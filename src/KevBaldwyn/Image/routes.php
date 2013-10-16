<?php

$route = rtrim(\Config::get('image::route'), '/');
Route::get($route, function() {
	App::make('kevbaldwyn.image')->serve();
});