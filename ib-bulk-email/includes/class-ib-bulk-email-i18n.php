<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://shetech.zya.me/
 * @since      1.0.0
 *
 * @package    Ib_Email_List
 * @subpackage Ib_Email_List/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Ib_Email_List
 * @subpackage Ib_Email_List/includes
 * @author     Shilpa Singh <shilpa@ibarts.in>
 */
class Ib_Email_List_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'ib-bulk-email',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
