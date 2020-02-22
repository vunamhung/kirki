<?php

namespace vnh;

use Kirki;
use vnh\contracts\Enqueueable;

class Setup_Kirki implements Enqueueable {
	public $config_id;

	public function __construct($config_id) {
		$this->config_id = $config_id;
	}

	public function boot() {
		add_filter('kirki_config', [$this, 'url_path']);
		add_action('widgets_init', [$this, 'add_config'], 99);
		add_action('customize_controls_init', [$this, 'enqueue']);
		add_filter('kirki_values_get_value', [$this, 'get_theme_mod_value'], 10, 2);

		add_filter('kirki_telemetry', '__return_false');
	}

	public function url_path($config) {
		$config['url_path'] = get_theme_file_uri('vendor/aristath/kirki/');

		return $config;
	}

	public function add_config() {
		Kirki::add_config($this->config_id, [
			'option_type' => 'theme_mod',
			'capability' => 'edit_theme_options',
		]);
	}

	public function enqueue() {
		wp_enqueue_style('kirki-custom-css', get_theme_file_uri('vendor/vunamhung/kirki/css/kirki.css'), [], '1.0.0');
	}

	public function get_theme_mod_value($value, $field_id) {
		if (empty($_GET)) {
			return $value;
		}

		$settings = [];

		foreach ($_GET as $key => $query_value) {
			if (empty(Kirki::$fields[$key])) {
				return $value;
			}

			$settings[$key] = $query_value;

			if (Kirki::$fields[$key]['type'] === 'kirki-select' && !empty(Kirki::$fields[$key]['args']['choices'][$query_value]['settings'])) {
				$preset_settings = Kirki::$fields[$key]['args']['choices'][$query_value]['settings'];
				foreach ($preset_settings as $field => $field_value) {
					$settings[$field] = $field_value;
				}
			}
		}

		if (isset($settings[$field_id])) {
			return $settings[$field_id];
		}

		return $value;
	}
}
