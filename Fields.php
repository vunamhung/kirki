<?php

namespace vnh;

use Kirki;
use vnh\contracts\Bootable;

abstract class Fields implements Bootable {
	public function boot() {
		add_action('widgets_init', [$this, 'build_fields'], 99);
	}

	abstract public function register_fields();

	public function build_fields() {
		$fields = $this->register_fields();

		if (empty($fields) || empty($fields['id'])) {
			return;
		}

		/*
		 * Add section
		 */
		$section_args = [];
		foreach ($fields as $index => $value) {
			if ($index === 'fields') {
				continue;
			}
			$section_args[$index] = $value;
		}

		if (!empty($section_args['title'])) {
			Kirki::add_section($section_args['id'], $section_args);
		}

		/*
		 * Add fields
		 */
		if (empty($fields['fields'])) {
			return;
		}

		foreach ($fields['fields'] as $group_fields_prefix => $group_fields_value) {
			foreach ($group_fields_value as $field_name => $field_value) {
				$args = [];
				$field_setting = sprintf('%s_%s_%s', $section_args['id'], $group_fields_prefix, $field_name);

				$args['section'] = $section_args['id'];
				$args['settings'] = $field_setting;

				foreach ($field_value as $label => $value) {
					if ($label === 'partial_refresh') {
						$args[$label] = [$field_setting => $value];
					} else {
						$args[$label] = $value;
					}
				}
				Kirki::add_field(THEME_SLUG, $args);
			}
		}
	}
}
