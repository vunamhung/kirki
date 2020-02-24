<?php

namespace vnh;

use vnh\contracts\Bootable;
use Kirki;

class Document_Section implements Bootable {
	public $args;
	public $config_id;

	public function __construct($config_id, $args) {
		$this->args = wp_parse_args($args,[
			'type' => 'kirki-link',
			'title' => __('Documentation', 'vnh_textdomain'),
			'button_text' => __('View Document', 'vnh_textdomain'),
			'button_url' => '',
			'priority' => 999,
		]);

		$this->config_id = $config_id;
	}

	public function boot() {
		add_action('widgets_init', [$this, 'register_section'], 99);
	}

	public function register_section() {
		Kirki::add_section($this->config_id, $this->args);
	}
}
