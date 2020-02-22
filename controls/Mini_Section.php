<?php

namespace vnh\controls;

use Kirki_Control_Base;

class Mini_Section extends Kirki_Control_Base {
	public $type = 'mini-section';

	public function render_content() {
		if (empty($this->description)) {
			printf('<div class="mini-section">%s</div>', esc_html($this->label));
		} else {
			printf(
				'<div class="mini-section">%s</div><div class="desc">%s</div>',
				esc_html($this->label),
				wp_kses_post($this->description)
			);
		}
	}
}
