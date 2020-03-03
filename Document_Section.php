<?php

namespace vnh;

use vnh\contracts\Bootable;
use Kirki;

class Document_Section implements Bootable {
	public $args;

	public function __construct($args) {
		$this->args = wp_parse_args($args,[
			'type' => 'kirki-link',
			'title' => __('Documentation', 'vnh_textdomain'),
			'button_text' => __('View Document', 'vnh_textdomain'),
			'button_url' => '',
			'priority' => 999,
		]);
	}

	public function boot() {
		add_action('widgets_init', [$this, 'register_section'], 99);
	}

	public function register_section() {
		Kirki::add_section(THEME_SLUG, $this->args);
	}
}
