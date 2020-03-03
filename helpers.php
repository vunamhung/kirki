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

function build_fields($fields) {
	if (empty($fields)) {
		return;
	}

	foreach ($fields as $section => $section_values) {
		/*
		 * Add section
		 */
		$section_args = [];
		foreach ($section_values as $index => $value) {
			if ($index === 'fields') {
				continue;
			}
			$section_args[$index] = $value;
		}

		if (!empty($section_values['title'])) {
			Kirki::add_section($section, $section_args);
		}

		/*
		 * Add fields
		 */
		if (empty($section_values['fields'])) {
			return;
		}

		foreach ($section_values['fields'] as $group_fields_prefix => $group_fields_value) {
			foreach ($group_fields_value as $field_name => $field_value) {
				$args = [];
				$args['section'] = $section;
				$field_setting = sprintf('%s_%s_%s', $section, $group_fields_prefix, $field_name);
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
