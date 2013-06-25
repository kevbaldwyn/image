<?php

Route::get('_img', function() {
	Image::serve();
});