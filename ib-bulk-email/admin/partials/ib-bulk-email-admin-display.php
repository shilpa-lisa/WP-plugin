<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://shetech.zya.me/
 * @since      1.0.0
 *
 * @package    Ib_Email_List
 * @subpackage Ib_Email_List/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<?php

    // Retrieve the saved email settings from the options table
    $email_settings = get_option('ib_email_settings', array());

    // Get the saved values or set default values if they don't exist
    $mail_template = isset($email_settings['mail_template']) ? $email_settings['mail_template'] : '';
    $sender_name = isset($email_settings['sender_name']) ? $email_settings['sender_name'] : '';
    $sender_email = isset($email_settings['sender_email']) ? $email_settings['sender_email'] : '';
    $cc_recipients = isset($email_settings['cc_recipients']) ? $email_settings['cc_recipients'] : '';
    $mail_subject = isset($email_settings['mail_subject']) ? $email_settings['mail_subject'] : '';

?>
<div class="ib-bulk-email">
<h1>Bulk Email Settings</h1>

<?php if (isset($success_message) || isset($_GET['success_message']) ) : ?>
    <div class="notice notice-success">
        <p><?php echo (isset($success_message)) ? $success_message : $_GET['success_message']; ?></p>
    </div>
<?php endif; ?>

<?php if (isset($error_message) || isset($_GET['error_message']) ) : ?>
    <div class="notice notice-error">
        <p><?php echo (isset($error_message)) ? $error_message : $_GET['error_message']; ?></p>
    </div>
<?php endif; ?>

<h2>Add Email</h2>
<form method="post" action="">
    <input type="email" name="email" placeholder="Enter Email">
    <input type="submit" name="submit_email" value="Add Email" class="button button-primary">
</form>


<h2>Bulk Email List</h2>
<form method="post" action="">
    <table class="wp-list-table widefat">
        <thead>
            <tr>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <?php
            global $wpdb;
            $table_name = $wpdb->prefix . 'email_list';
            $emails = $wpdb->get_results("SELECT * FROM $table_name");
            foreach ($emails as $email) :
            ?>
                <tr>
                    <td class="added-email-id"><?php echo $email->email; ?></td>
                    <td>
                        <form method="post" onsubmit="return confirm('Are you sure you want to delete this email?');">
                            <input type="hidden" name="email_id" value="<?php echo $email->id; ?>">
                            <input type="submit" name="delete_email" value="Delete" class="button button-secondary delete-icon">
                            
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Send Emails</h2>
    <div class="sender-info">
    <div class="sender-name">
    <label for="sender_name">Sender Name:</label>
    <input id="sender_name" name="sender_name" type="text" value="<?php echo stripslashes($sender_name); ?>" />
    </div>
    <br>
    <div class="sender-mail">
    <label for="sender_email">Sender Email:</label>
    <input id="sender_email" name="sender_email" type="email" value="<?php echo $sender_email; ?>" />
    </div>
    <br>
    </div>
    <label for="cc_recipients">CC Recipients (comma-separated):</label>
    <input id="cc_recipients" name="cc_recipients" type="text" value="<?php echo $cc_recipients; ?>" />
    <br>
    <label for="mail_subject">Mail Subject:</label>
    <input id="mail_subject" name="mail_subject" type="text" value="<?php echo $mail_subject; ?>" />
    <br>
    <label for="mail_template">Mail Template:</label>
    <textarea id="mail_template" name="mail_template" rows="5"><?php echo $mail_template; ?></textarea>
    <br>
    <input type="submit" name="send_emails" value="Send Emails" class="button button-primary">
</form>
</div>

<?php
// Add the code to trigger the email sending process
if (isset($_POST['send_emails'])) {
    // Trigger the action to send emails
    do_action('ib_send_emails');
}

// Handle email deletion
if (isset($_POST['delete_email'])) {
    // var_dump($_POST['delete_email']);
    // die();
    // Handle email deletion
    do_action('ib_delete_email');
}
?>