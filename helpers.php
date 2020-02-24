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

function build_fields($fields,$config_id) {
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
				Kirki::add_field($config_id, $args);
			}
		}
	}
}

function build_preset_options($config, $presets, $section_name) {
	$all_setting_ids = get_all_setting_ids($config, $section_name);

	$all_field_default_values = [];
	foreach ($all_setting_ids as $field) {
		$all_field_default_values[$field] = Kirki::$fields[$field]['default'];
	}

	$complete_preset = [];
	$complete_preset['1']['settings'] = $all_field_default_values;
	$preset = isset($presets[$section_name]) ? $presets[$section_name] : [];
	foreach ($preset as $preset_name => $section_value) {
		$complete_preset[$preset_name]['label'] = $preset[$preset_name]['label'];
		foreach ($section_value['settings'] as $group_prefix => $group_value) {
			foreach ($group_value as $field_name => $field_value) {
				$name = sprintf('%s_%s_%s', $section_name, $group_prefix, $field_name);
				$complete_preset[$preset_name]['settings'][$all_setting_ids[$name]] = $field_value;

				$complete_preset[$preset_name]['settings'] = wp_parse_args(
					$complete_preset[$preset_name]['settings'],
					$all_field_default_values
				);
			}
		}
	}

	return $complete_preset;
}
