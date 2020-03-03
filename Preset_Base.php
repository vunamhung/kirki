<?php

namespace vnh;

use Kirki;

abstract class Preset_Base extends Section_Base {
	public $section_name;
	public $fields;

	public function __construct($fields) {
		$this->fields = $fields;
		$this->section_name = key($this->register_fields());
	}

	abstract public function register_preset_options();

	protected function get_preset_options() {
		$all_setting_ids = $this->get_all_setting_ids($this->fields, $this->section_name);
		$presets = $this->register_preset_options();
		$all_field_default_values = [];

		foreach ($all_setting_ids as $field) {
			$all_field_default_values[$field] = Kirki::$fields[$field]['default'];
		}

		$complete_preset = [];
		$complete_preset['1']['settings'] = $all_field_default_values;
		$preset = isset($presets[$this->section_name]) ? $presets[$this->section_name] : [];
		foreach ($preset as $preset_name => $section_value) {
			$complete_preset[$preset_name]['label'] = $preset[$preset_name]['label'];
			foreach ($section_value['settings'] as $group_prefix => $group_value) {
				foreach ($group_value as $field_name => $field_value) {
					$name = sprintf('%s_%s_%s', $this->section_name, $group_prefix, $field_name);
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

	private function get_all_setting_ids($config, $section_name) {
		$all_setting_ids = [];
		foreach ($config as $section => $section_value) {
			if ($section !== $section_name) {
				continue;
			}

			foreach ($section_value['fields'] as $group_fields_prefix => $group_fields_value) {
				foreach ($group_fields_value as $field_name => $field_value) {
					if (in_array($field_value['type'], ['preset', 'mini-section', 'mini-group', 'custom'], true)) {
						continue;
					}
					$name = sprintf('%s_%s_%s', $section, $group_fields_prefix, $field_name);
					$all_setting_ids[$name] = $name;
				}
			}
		}

		return $all_setting_ids;
	}
}
