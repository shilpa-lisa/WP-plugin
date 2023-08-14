<?php

/**
 * Fired during plugin activation
 *
 * @link       http://shetech.zya.me/
 * @since      1.0.0
 *
 * @package    Ib_Email_List
 * @subpackage Ib_Email_List/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Ib_Email_List
 * @subpackage Ib_Email_List/includes
 * @author     Shilpa Singh <shilpa@ibarts.in>
 */
class Ib_Email_List_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'email_list';
    
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            email varchar(255) NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";
    
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
	}

}
