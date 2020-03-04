<?php

namespace vnh;

use Kirki;

function get_layout($layouts) {
	$layouts_array = [];
	foreach ($layouts as $layout) {
		$layouts_array[$layout] = get_theme_file_uri("vendor/vunamhung/kirki/images/$layout.png");
	}

	return $layouts_array;
}
