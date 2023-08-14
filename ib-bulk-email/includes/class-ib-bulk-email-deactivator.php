<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://shetech.zya.me/
 * @since      1.0.0
 *
 * @package    Ib_Email_List
 * @subpackage Ib_Email_List/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Ib_Email_List
 * @subpackage Ib_Email_List/includes
 * @author     Shilpa Singh <shilpa@ibarts.in>
 */
class Ib_Email_List_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
	    
	    global $wpdb;
        $table_name = $wpdb->prefix . 'email_list';
        $wpdb->query("DROP TABLE IF EXISTS $table_name");
        
        // Delete the key and value from the wp_options table
        delete_option( 'ib_email_settings' );

	}

}
