<?php

namespace vnh;

use vnh\contracts\Bootable;

abstract class Section_Base implements Bootable {
	public $fields;
	public $config_id;

	public function __construct($config_id) {
		$this->config_id = $config_id;
	}

	public function boot() {
		add_action('widgets_init', [$this, 'build_fields'], 99);
	}

	abstract public function register_fields();

	public function build_fields() {
		build_fields($this->register_fields(), $this->config_id);
	}
}
