<?php

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Opengraphiq_i18n {

	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'opengraphiq-lite',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}
}
