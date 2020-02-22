<?php

namespace vnh\controls;

use Kirki_Control_Base;

class Mini_Group extends Kirki_Control_Base {
	public $type = 'mini-group';

	public function render_content() {
		if (empty($this->description)) {
			printf('<div class="mini-group">%s</div>', esc_html($this->label));
		} else {
			printf('<div class="mini-group"><i class="dashicons %s"/>%s</div>', esc_attr($this->description), esc_html($this->label));
		}
	}
}
