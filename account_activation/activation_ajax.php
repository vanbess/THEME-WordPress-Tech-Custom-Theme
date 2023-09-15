<?php

/**
 * AJAX to update/activate user account
 */

defined('ABSPATH') ?: exit();

add_action('wp_ajax_nopriv_extech_acc_activation', 'extech_acc_activation');
add_action('wp_ajax_extech_acc_activation', 'extech_acc_activation');

function extech_acc_activation() {

    error_log('>>>>>>>>>>>>>>>>>> ACCOUNT ACTIVATION START <<<<<<<<<<<<<<<<<<<');
    error_log('Checking AJAX referrer...');

    check_ajax_referer('extech activate account update password');

    error_log('Check successful, grabbing subbed password, site and user data');

    $owner_id       = isset($_POST['oid']) ? (int)$_POST['oid'] : false;
    $activation_key = isset($_POST['akey']) ? $_POST['akey'] : false;
    $child_site_id  = isset($_POST['sid']) ? (int)$_POST['sid'] : false;
    $pass           = isset($_POST['pass']) ? $_POST['pass'] : false;

    // bail early if any of our subbed vars are not present
    if (!$owner_id || !$activation_key || !$child_site_id || !$pass) :

        error_log('One or more subbed variables missing, bailing');

        wp_die('All required variables not present, aborting');

    endif;

    error_log('Retrieving child site URL');

    // grab site url from site id
    $site_url = get_site_url($child_site_id);

    // set up login url
    $login_url = $site_url . '/dashboard/log-in/';

    error_log('Setting dashboard URL for child site to ' . $login_url);
    error_log('Password form submitted, beginning processing');
    error_log('Attempting to retrieve child site data using get_blog_details()');

    $child_site_dets = get_blog_details($child_site_id);

    if (!$child_site_dets) :
        error_log('Could not retrieve child site details for some reason. Additional debugging required. Bailing.');
    else :

        error_log('Child site details retrieved successfully. Details are: ' . print_r($child_site_dets, true));
        error_log('Switching to correct site context: site ID ' . $child_site_id);

        switch_to_blog($child_site_id);

        error_log('Checking if switch has been successful');

        if (get_current_blog_id() === $child_site_id) :
            error_log('Site switch was successful, continuing');
        else :
            error_log('Site switch was not successful, additional debugging required as account activation is likely to fail');
        endif;

        // update user pass
        $user_data = array(
            'ID'        => $owner_id,
            'user_pass' => $pass
        );

        $pass_set = wp_update_user($user_data);

        // if password set successfully, show success html and send email, else show failure html and ask user to try again
        if (!is_wp_error($pass_set)) :

            error_log('Password successfully set, sending user email with password details');

            // Send an email to the user with their new password
            $user    = get_user_by('ID', $owner_id);
            $to      = $user->user_email;
            $subject = 'Your password for Excellerate Convenience';
            $message = '<h2>Good day ' . $user->first_name . ' ' . $user->last_name . '</h2>';
            $message .= '<p>You recently activated your account and set up your password.</p>';
            $message .= '<p>Your password is: <b>' . $pass . '</b></p>';
            $message .= '<p>Be sure to save this password in a safe place!</p>';
            $message .= "<p>You may now log in at <a href='$login_url'><b>this link.</b></a></p>";
            $message .= '<p></p>';
            $message .= '<p><b>Best wishes,</b></p>';
            $message .= '<p><b>The Excellerate Convenience Team</b></p>';
            $headers = array('Content-Type: text/html; charset=UTF-8');

            wp_mail($to, $subject, $message, $headers);

            error_log('Activating account...');

            // update user account activation status
            $activated = update_user_meta($owner_id, 'acc_active', true);

            if (is_int($activated) || $activated) :
                error_log('Account successfully activated');
                wp_send_json_success($user);

                error_log('Switching back to main site context');
                restore_current_blog();
                error_log('>>>>>>>>>>>>>>>>>> ACCOUNT ACTIVATION END <<<<<<<<<<<<<<<<<<<');
            else :
                error_log('Account could not be activated, probably because it is already activated, however password has been set.');
                wp_send_json_success($user);

                error_log('Switching back to main site context');
                restore_current_blog();
                error_log('>>>>>>>>>>>>>>>>>> ACCOUNT ACTIVATION END <<<<<<<<<<<<<<<<<<<');
            endif;

        elseif (is_wp_error($pass_set)) :
            error_log('Password could not be saved, password update failed for site ID ' . $child_site_id . ' and user ID ' . $owner_id . ' with the following error: ' . $pass_set->get_error_message());
            wp_send_json_error('Password could not be set because because of the following error: ' . $pass_set->get_error_message());
            error_log('Switching back to main site context');
    
            restore_current_blog();
    
            error_log('>>>>>>>>>>>>>>>>>> ACCOUNT ACTIVATION END <<<<<<<<<<<<<<<<<<<');
        endif;

    endif;

    wp_die();
}
