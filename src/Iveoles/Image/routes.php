<?php

$route = rtrim(\Config::get('image::route'), '/');
Route::get($route, function() {
	App::make('iveoles.image')->serve();
});