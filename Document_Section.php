<?php

namespace vnh;

use vnh\contracts\Bootable;
use Kirki;

class Document_Section implements Bootable {
	public $args;

	public function boot() {
		add_action('widgets_init', [$this, 'register_section'], 99);
	}

	public function register_section() {
		$defaults = [
			'type' => 'kirki-link',
			'title' => esc_html__('Documentation', 'vnh_textdomain'),
			'button_text' => esc_html__('View Document', 'vnh_textdomain'),
			'button_url' => THEME_DOCUMENT_URI,
			'priority' => 999,
		];

		Kirki::add_section(THEME_SLUG, wp_parse_args($this->args, $defaults));
	}
}
