<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://shetech.zya.me/
 * @since      1.0.0
 *
 * @package    Ib_Email_List
 * @subpackage Ib_Email_List/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Ib_Email_List
 * @subpackage Ib_Email_List/admin
 * @author     Shilpa Singh <shilpa@ibarts.in>
 */
class Ib_Email_List_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Ib_Email_List_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ib_Email_List_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ib-bulk-email-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Ib_Email_List_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ib_Email_List_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ib-bulk-email-admin.js', array( 'jquery' ), $this->version, false );

	}
	
	/**
	 * Register the wordpress menu for the admin area.
	 * 
	 * @since   1.0.0
	 */
	public function ib_admin_menu() {
	   add_menu_page( 
    		__( 'Bulk Email', 'textdomain' ),
    		'Bulk Email',
    		'manage_options',
    		'ib-bulk-email',
    		array($this, 'ib_email_list_menu_page'), 
    		'dashicons-email-alt',
    		6
    	);  
	}
	
	/**
     * Display a custom menu page
     */
    function ib_email_list_menu_page(){
        // Check if the user has sufficient permissions
        if (!current_user_can('manage_options')) {
            wp_die('You do not have sufficient permissions to access this page.');
        }
        
        // Handle form submissions
        if (isset($_POST['submit_email'])) {
            $email = sanitize_email($_POST['email']);

            // Retrieve the mail template from the form input
            $mail_template = isset($_POST['mail_template']) ? wp_kses_post($_POST['mail_template']) : '';
            
            if (empty($email)) {
                $error_message = 'Please provide a valid email address.';
            } else {
                // Save the email to the database
                $this->save_email($email);
                $success_message = 'Email successfully added.';
            }
        }
        
        include_once(plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/ib-bulk-email-admin-display.php');
        // echo plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/ib-bulk-email-admin-display.php';
    }
    
    private function save_email($email) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'email_list';
        $wpdb->insert(
            $table_name,
            array('email' => $email),
            array('%s')
        );
    }
    
    private function save_email_settings($mail_template, $mail_subject, $sender_name, $sender_email, $cc_recipients) {
        $email_settings = array(
            'mail_template' => $mail_template,
            'mail_subject' => $mail_subject,
            'sender_name' => $sender_name,
            'sender_email' => $sender_email,
            'cc_recipients' => $cc_recipients,
        );
    
        update_option('ib_email_settings', $email_settings);
    }
    
    public function handle_send_emails() {
        if (!current_user_can('manage_options')) {
            wp_die('You do not have sufficient permissions to access this page.');
        }
    
        if (isset($_POST['send_emails'])) {
            
            // var_dump($_POST['send_emails']);
            // die();
            
            global $wpdb;
            $table_name = $wpdb->prefix . 'email_list';
            $emails = $wpdb->get_results("SELECT * FROM $table_name");
    
            // Get the mail template from the form input
            $mail_template = isset($_POST['mail_template']) ? $_POST['mail_template'] : '';
            // Get the mail subject from the form input
            $mail_subject = isset($_POST['mail_subject']) ? $_POST['mail_subject'] : '';
            // Get the mail sender name from the form input
            $sender_name = isset($_POST['sender_name']) ? $_POST['sender_name'] : '';
            // Get the mail sender email from the form input
            $sender_email = isset($_POST['sender_email']) ? $_POST['sender_email'] : '';
            // Get the CC recipients from the form input
            $cc_recipients = isset($_POST['cc_recipients']) ? $_POST['cc_recipients'] : '';
        
            
            // Save the email settings
            $this->save_email_settings($mail_template, $mail_subject, $sender_name, $sender_email, $cc_recipients);
            
            foreach ($emails as $email) {
                $to = $email->email;
                $subject = $mail_subject; // Use the mail subject as the message subject
                $message = $mail_template; // Use the mail template as the message content
                $headers = array('Content-Type: text/html; charset=UTF-8');
                $headers[] = 'From: ' . $sender_name . ' <' . $sender_email . '>';
    
                // Add CC recipients to headers
                if (!empty($cc_recipients)) {
                    $cc_recipients_array = explode(',', $cc_recipients);
                    foreach ($cc_recipients_array as $cc) {
                        $cc = trim($cc);
                        if (!empty($cc)) {
                            $headers[] = 'Cc: ' . $cc;
                        }
                    }
                }
    
                // Send email using WordPress function
                $email_sent = wp_mail($to, $subject, $message, $headers);
            }
    
            if($email_sent){
                $success_message = 'Email Sent Successfully';
                wp_redirect(admin_url('admin.php?page=ib-bulk-email&success_message=' . urlencode($success_message)));
            } else {
                $error_message = 'Failed to send email';
                wp_redirect(admin_url('admin.php?page=ib-bulk-email&error_message=' . urlencode($error_message)));
            }
            // Redirect back to the admin page
            // wp_redirect(admin_url('admin.php?page=ib-bulk-email&success_message=' . urlencode($success_message)));
            exit;
        }
    }
    
    public function handle_delete_emails() {
        
        if (!current_user_can('manage_options')) {
            wp_die('You do not have sufficient permissions to access this page.');
        }
        // Handle email deletion
        if (isset($_POST['delete_email'])) {
            $email_id = absint($_POST['email_id']);
    
            if ($email_id > 0) {
                // Delete the email from the database
                global $wpdb;
                $table_name = $wpdb->prefix . 'email_list';
                $wpdb->delete($table_name, array('id' => $email_id));
    
                // Display a success message
                $success_message = 'Email deleted successfully.';
                wp_redirect(admin_url('admin.php?page=ib-bulk-email&success_message=' . urlencode($success_message)));
            } else {
                // Display an error message if the email ID is invalid
                $error_message = 'Invalid email ID.';
                wp_redirect(admin_url('admin.php?page=ib-bulk-email&error_message=' . urlencode($error_message)));
            }
        }
    
        // Redirect back to the admin page
        // wp_redirect(admin_url('admin.php?page=ib-bulk-email&success_message=' . urlencode($success_message)));
        exit;
    }

    private function get_email_by_id($email_id) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'email_list';
        $query = $wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $email_id);
        return $wpdb->get_row($query);
    }

}