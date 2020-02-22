<?php

namespace vnh;

use vnh\contracts\Bootable;
use vnh\controls\Mini_Group;
use vnh\controls\Mini_Section;

class Register_Custom_Controls implements Bootable {
	public function boot() {
		add_action('customize_register', [$this, 'register_custom_controls']);
	}

	public function register_custom_controls() {
		add_filter('kirki_control_types', [$this, 'add_custom_controls']);
	}

	public function add_custom_controls($controls) {
		$controls['mini-section'] = Mini_Section::class;
		$controls['mini-group'] = Mini_Group::class;

		return $controls;
	}
}
