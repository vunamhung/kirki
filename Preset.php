<?php

namespace vnh;

use Kirki;

abstract class Preset extends Fields {
	public $section_id;
	public $fields;

	abstract public function register_preset_options();

	protected function get_preset_options() {
		$all_setting_ids = $this->get_all_setting_ids();
		$presets = $this->register_preset_options();
		$all_field_default_values = [];

		foreach ($all_setting_ids as $field) {
			$all_field_default_values[$field] = Kirki::$fields[$field]['default'];
		}

		$complete_preset = [];
		$complete_preset['1']['settings'] = $all_field_default_values;

		foreach ($presets as $preset_name => $section_value) {
			$complete_preset[$preset_name]['label'] = $presets[$preset_name]['label'];
			foreach ($section_value['settings'] as $group_prefix => $group_value) {
				foreach ($group_value as $field_name => $field_value) {
					$name = sprintf('%s_%s_%s', $this->section_id, $group_prefix, $field_name);
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

	private function get_all_setting_ids() {
		$all_setting_ids = [];

		foreach ($this->fields['fields'] as $group_fields_prefix => $group_fields_value) {
			foreach ($group_fields_value as $field_name => $field_value) {
				if (in_array($field_value['type'], ['preset', 'mini-section', 'mini-group', 'custom'], true)) {
					continue;
				}
				$name = sprintf('%s_%s_%s', $this->section_id, $group_fields_prefix, $field_name);
				$all_setting_ids[$name] = $name;
			}
		}

		return $all_setting_ids;
	}
}
