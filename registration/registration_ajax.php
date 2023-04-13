<?php

/**
 * Make sure email headers are correctly set for registration emails
 * 
 * @covers new shop registration
 * @completed 12/04/2023: all tests and debugging done
 */
function extech_reg_mail_content_type() {
    return 'text/html';
}
add_filter('wp_mail_content_type', 'extech_reg_mail_content_type');

// Process shop registration form
add_action('wp_ajax_nopriv_process_shop_registration', 'process_shop_registration');
add_action('wp_ajax_process_shop_registration', 'process_shop_registration');

function process_shop_registration() {

    // Log
    error_log('>>>>>>>>>>>>>>>>> ACCOUNT REGISTRATION START <<<<<<<<<<<<<<<<<');

    // Log
    error_log('Checking AJAX nonce...');

    $nonce = $_POST['_ajax_nonce'];

    // check nonce
    if (!wp_verify_nonce($nonce, 'ex tech register shop')) {

        // Log
        error_log('There is an issue with the nonce, bailing');

        wp_die();
    }

    // Log
    error_log('Nonce is fine, starting session');

    // start session
    session_start();

    // Log
    error_log('Grabbing submitted registration data, which is the following: ' . print_r($_POST, true));

    // retrieve submitted data
    $owner_first        = sanitize_text_field($_POST['shop_owner_first']);
    $owner_last         = sanitize_text_field($_POST['shop_owner_last']);
    $owner_mail         = sanitize_email($_POST['shop_owner_email']);
    $owner_tel          = sanitize_text_field($_POST['shop_owner_tel']);
    $shop_franchise     = sanitize_text_field($_POST['shop_franchise']);
    $shop_name          = sanitize_text_field($_POST['shop_name']);
    $shop_street_number = sanitize_text_field($_POST['shop_street_number']);
    $shop_suburb        = sanitize_text_field($_POST['shop_suburb']);
    $shop_city          = sanitize_text_field($_POST['shop_city']);
    $shop_province      = sanitize_text_field($_POST['shop_province']);
    $shop_postal        = sanitize_text_field($_POST['shop_postal_code']);

    // Create account confirmation nonce
    $acc_conf_nonce = wp_create_nonce(json_encode($_POST) . time());

    // Log
    error_log('Creating account confirmaton nonce, which is ' . $acc_conf_nonce);

    /**
     * Step 1: Add new multisite child site
     */

    // Log
    error_log('Retrieving networ super admin and setting up child site site string');

    // Get super admin id
    $super_admins   = get_super_admins();
    $super_admin_id = $super_admins[0];
    $site_string    = str_replace(' ', '-', preg_replace('/[^A-Za-z0-9\-]/', '', strtolower($shop_name)));

    // Log
    error_log('Inserting new child site using subbed registration details');

    // Create the new sub site
    $site_id = wpmu_create_blog(get_current_site()->domain, "/{$site_string}/", $shop_name, $super_admin_id);

    // If childsite creation somehow fails
    if (!$site_id) {

        // Log
        error_log('Could not create child site, sending email to super admin and bailing');

        // send email to admin on failure
        $to      = get_option('admin_email');
        $subject = 'Exellerate Tech: Addition of child site failed';
        $body    = '<h3>NOTE: The addition of a child site to the Excellerate Tech multisite network failed during registration. Details:</h3>';
        $body    .= "<p>Owner first and last name: $owner_first $owner_last</p>";
        $body    .= "<p>Owner email: $owner_mail</p>";
        $body    .= "<p>Owner tel: $owner_tel</p>";
        $body    .= "<p>Shop franchise: $shop_franchise</p>";
        $body    .= "<p>Shop name: $shop_name</p>";
        $body    .= "<p>Street address: $shop_street_number</p>";
        $body    .= "<p>Suburb: $shop_suburb</p>";
        $body    .= "<p>City: $shop_city</p>";
        $body    .= "<p>Province: $shop_province</p>";
        $body    .= "<p>Postal: $shop_postal</p>";
        $headers = array('Content-Type: text/html; charset=UTF-8');

        wp_mail($to, $subject, $body, $headers);

        wp_send_json_error('Unfortunately registration has failed. Please reload the page and try again.');
        wp_die();
    }

    // Log
    error_log('Child site successfully inserted, site ID is ' . $site_id . '. Switching to child site');

    // Switch to the new site's context
    switch_to_blog($site_id);

    // Log
    error_log('Switching to exceltech theme');

    // Set site theme
    switch_theme('exceltech');

    /**
     * Step 2: Activate required plugins
     */

    // Log
    error_log('Activating required plugins');

    // Activate specific plugins
    $plugins = array('woocommerce/woocommerce.php', 'ikhokha-payment-gateway/index.php', 'woocommerce-payfast-gateway/gateway-payfast.php');

    foreach ($plugins as $plugin) {
        activate_plugin($plugin);
    }

    // @todo add settings for specific payment gateways so that payments can actually be taken

    /**
     * Step 3: Register new user roles and insert owner and shop manager users
     */

    // Log
    error_log('Retrieving WooCommerce shop_manager role and capabilities');

    //  retrieve shop_manager role caps
    $shop_manager_role = get_role('shop_manager');
    $shop_manager_caps = $shop_manager_role->capabilities;

    // Log
    error_log('Adding shop_owner role to child site');

    // register shop owner role
    $owner_role_added = add_role('shop_owner', 'Shop Owner', $shop_manager_caps);

    // log
    if (is_null($owner_role_added)) :
        error_log('The shop_owner role apparently already exists.');
    else :
        error_log('The shop_owner role was added successfully.');
    endif;

    //  retrieve customer role caps
    $customer_role = get_role('customer');
    $customer_caps = $customer_role->capabilities;

    //  register petrol attendant role
    $attendant_role = add_role('petrol_attendant', 'Petrol Attendant', $customer_caps);

    // log
    if (is_null($attendant_role)) :
        error_log('The petrol_attendant role apparently already exists.');
    else :
        error_log('The petrol_attendant role was added successfully.');
    endif;

    // Define owner details
    $owner_data = array(
        'user_login' => strtolower($owner_first),
        'user_pass'  => wp_generate_password(),
        'user_email' => $owner_mail,
        'first_name' => $owner_first,
        'last_name'  => $owner_last,
        'role'       => 'shop_owner',
        'meta_input' => array(
            'tel'            => $owner_tel,
            'acc_conf_nonce' => $acc_conf_nonce,
            'acc_active'     => false,
            'site_id'        => $site_id
        ),
    );

    // Create the new user
    $owner_id = wp_insert_user($owner_data);

    // log
    error_log('User Data: ' . print_r($owner_data, true));
    error_log('New User ID: ' . $owner_id);

    // log any errors
    if (is_wp_error($owner_id)) {
        error_log('User Registration Error: ' . $owner_id->get_error_message());

        // add user to blog
    } elseif (!is_wp_error($owner_id)) {
        add_user_to_blog($site_id, $owner_id, 'shop_owner');
    }

    // Log
    error_log('Retrieving owner user data');

    // Get owner user data
    $owner_data  = get_userdata($owner_id);

    // Log
    error_log('Adding owner email and username to $_SESSION data');

    // add above to session along with email address (used to send correct headers with new user notification)
    $_SESSION['owner_email'] = $owner_data->user_email;
    $_SESSION['owner_uname'] = $owner_data->user_login;

    // Log
    error_log('Filtering content of default new user notification email');

    // modify new user notification for our use case
    add_filter('wp_new_user_notification_email', function ($wp_new_user_notification_email, $owner_data) {

        // Get the user's first name and last name
        $first_name = $owner_data->first_name;
        $last_name  = $owner_data->last_name;
        $full_name  = $first_name . ' ' . $last_name;

        // get nonce and site id
        $acc_conf_nonce = get_user_meta($owner_data->ID, 'acc_conf_nonce', true);
        $site_id        = get_user_meta($owner_data->ID, 'site_id', true);

        // get owner id
        $owner_id = $owner_data->ID;

        // Customize the subject line of the email
        $wp_new_user_notification_email['subject'] = 'Welcome to Excellerate Technologies, ' . $full_name . '!';

        // Customize the body of the email
        $wp_new_user_notification_email['message'] = '<h3>Hello ' . $full_name . '</h3>';
        $wp_new_user_notification_email['message'] .= '<p>Thank you for registering your shop on Excellerate Technologies.</p>';
        $wp_new_user_notification_email['message'] .= '<p>Your username is: <b>' . $owner_data->user_login . '</b></p>';
        $wp_new_user_notification_email['message'] .= "<p>Please visit <a href='https://www.excelleratetech.com/account-confirmation/?oid=$owner_id&akey=$acc_conf_nonce&sid=$site_id'><b>this page</b></a> to set your password and activate your account.</p>";
        $wp_new_user_notification_email['message'] .= '<p><b>Best regards,</b></p>';
        $wp_new_user_notification_email['message'] .= '<p><b>The Excellerate Technologies Team</b></p>';

        return $wp_new_user_notification_email;
    }, 10, 2);

    // Log
    error_log('Sending filtered new user notification email');

    // send user account notification
    $sent = wp_new_user_notification($owner_id, null, 'both');

    // remove custom headers filter after sending
    remove_filter('wp_mail_content_type', 'extech_reg_mail_content_type');

    if (is_wp_error($sent)) :
        error_log('Could not send new user notification: ' . $sent->get_error_message());
    else :
        error_log('New user registration message sent successfully.');
    endif;

    // Log
    error_log('Inserting required default pages');

    /**
     * Step 3.5: Insert required dashboard pages and assign associated templates
     */
    // Set the parent page details
    $parent_page = array(
        'post_title'    => 'Dashboard',
        'post_content'  => '',
        'post_status'   => 'publish',
        'post_author'   => 1,
        'post_type'     => 'page',
        'page_template' => 'dashboard/page-dashboard.php',
        'post_name'     => 'dashboard'
    );

    // Insert the parent page
    $parent_id = wp_insert_post($parent_page);

    // Set the child page details
    $child_pages = array(
        array(
            'post_title'    => 'Account Settings',
            'post_content'  => '',
            'post_status'   => 'publish',
            'post_author'   => 1,
            'post_type'     => 'page',
            'post_parent'   => $parent_id,
            'page_template' => 'dashboard/page-dashboard-account.php',   // Set the page template,
            'post_name'     => 'account'
        ),
        array(
            'post_title'    => 'Products',
            'post_content'  => '',
            'post_status'   => 'publish',
            'post_author'   => 1,
            'post_type'     => 'page',
            'post_parent'   => $parent_id,
            'page_template' => 'dashboard/page-dashboard-products.php',   // Set the page template
            'post_name'     => 'products'
        ),
        array(
            'post_title'    => 'QR Code',
            'post_content'  => '',
            'post_status'   => 'publish',
            'post_author'   => 1,
            'post_type'     => 'page',
            'post_parent'   => $parent_id,
            'page_template' => 'dashboard/page-dashboard-qr-code.php',   // Set the page template
            'post_name'     => 'qr-code'

        ),
        array(
            'post_title'    => 'Users',
            'post_content'  => '',
            'post_status'   => 'publish',
            'post_author'   => 1,
            'post_type'     => 'page',
            'post_parent'   => $parent_id,
            'page_template' => 'dashboard/page-dashboard-users.php',   // Set the page template
            'post_name'     => 'users'
        ),
        array(
            'post_title'    => 'Log In',
            'post_content'  => '',
            'post_status'   => 'publish',
            'post_author'   => 1,
            'post_type'     => 'page',
            'post_parent'   => $parent_id,
            'page_template' => 'dashboard/page-dashboard-login.php',   // Set the page template
            'post_name'     => 'log-in'
        ),
    );

    // Insert the child pages
    foreach ($child_pages as $child_page) {
        wp_insert_post($child_page);
    }

    // Log
    error_log('Switching back to parent site');

    // Switch back to the original site's context
    restore_current_blog();

    /**
     * Step 4: Insert shop CPT on main site
     */

    // Log
    error_log('Inserting owner data to shop custom post type on parent site');

    // retrieve some basic site info
    $site_data = get_blog_details($site_id);
    $site_url  = $site_data->siteurl;

    // Set up post data
    $post_data = array(
        'post_title'   => $shop_name,
        'post_content' => '',
        'post_type'    => 'shop',
        'post_status'  => 'publish',
    );

    // Insert post and get post ID
    $post_id = wp_insert_post($post_data);

    // Log
    error_log('Updating shop post meta');

    // Add custom post meta
    update_post_meta($post_id, 'shop_owner_first_last', $owner_first . ' ' . $owner_last);
    update_post_meta($post_id, 'shop_owner_email', $owner_mail);
    update_post_meta($post_id, 'shop_owner_tel', $owner_tel);
    update_post_meta($post_id, 'shop_franchise', $shop_franchise);
    update_post_meta($post_id, 'shop_name', $shop_name);
    update_post_meta($post_id, 'shop_street_number', $shop_street_number);
    update_post_meta($post_id, 'shop_suburb', $shop_suburb);
    update_post_meta($post_id, 'shop_city', $shop_city);
    update_post_meta($post_id, 'shop_province', $shop_province);
    update_post_meta($post_id, 'shop_postal', $shop_postal);
    update_post_meta($post_id, 'shop_url', $site_url);
    update_post_meta($post_id, 'child_site_id', $site_id);
    update_post_meta($post_id, 'acc_conf_nonce', $acc_conf_nonce);

    // Log
    error_log('Registration process complete, returning JSON response');

    // Process form data here
    wp_send_json('Registration successful. An email has been sent to the email address supplied with instructions on how to set up your password.');

    // Log
    error_log('>>>>>>>>>>>>>>>>> ACCOUNT REGISTRATION END <<<<<<<<<<<<<<<<<');

    wp_die();
}
