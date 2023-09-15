<?php

// prevent direct access
if (!defined('ABSPATH')) exit;

/**
 * Re-arrange uploaded files array to make it loopable
 * 
 * @param array $file_post
 * @return array
 */
function reArrayFiles($file_post)
{

    $file_arr   = array();
    $file_count = count($file_post['name']);
    $file_keys  = array_keys($file_post);

    for ($i = 0; $i < $file_count; $i++) {
        foreach ($file_keys as $key) {
            $file_arr[$i][$key] = $file_post[$key][$i];
        }
    }

    return $file_arr;
}

/**
 * Make sure email headers are correctly set for registration emails
 * 
 * @covers new shop registration
 * @completed 12/04/2023: all tests and debugging done
 */
function extech_reg_mail_content_type()
{
    return 'text/html';
}
add_filter('wp_mail_content_type', 'extech_reg_mail_content_type');

// Process shop registration form
add_action('wp_ajax_nopriv_process_shop_registration', 'process_shop_registration');
add_action('wp_ajax_process_shop_registration', 'process_shop_registration');

function process_shop_registration()
{

    // debug
    // wp_send_json($_POST);

    // debug files
    // wp_send_json($_FILES);

    try {
        //code...


        // Log
        error_log('>>>>>>>>>>>>>>>>> ACCOUNT REGISTRATION START <<<<<<<<<<<<<<<<<');

        // Log
        error_log('Checking AJAX nonce...');

        $nonce = $_POST['_ajax_nonce'];

        // check nonce
        if (!wp_verify_nonce($nonce, 'ex tech register shop')) {

            // Log
            error_log('There is an issue with the registration nonce, bailing');

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

        // documentation files (make loopable)
        $shop_bus_reg_docs = reArrayFiles($_FILES['shop_bus_reg_docs']);
        $shop_director_ids = reArrayFiles($_FILES['shop_director_ids']);
        $shop_bank_acc     = reArrayFiles($_FILES['shop_bank_acc']);

        // Create account confirmation nonce
        $acc_conf_nonce = wp_create_nonce(json_encode($_POST) . time());

        // Log
        error_log('Creating account confirmaton nonce, which is ' . $acc_conf_nonce);

        /**
         * Step 1: Add new multisite child site
         */

        // Log
        error_log('Retrieving network super admin and setting up child site site string');

        // Get super admin id
        $super_admins   = get_super_admins();
        $super_admin_id = $super_admins[0];
        $site_string    = str_replace(' ', '-', preg_replace('/[^A-Za-z0-9\-]/', '', strtolower($shop_name)));

        // Log
        error_log('Inserting new child site using subbed registration details');

        // Create the new sub site
        $site_id = wpmu_create_blog(get_current_site()->domain, "/{$site_string}/", $shop_name, $super_admin_id);

        // is if wp error, send error message and bail
        if (is_wp_error($site_id)) {

            // Log
            error_log('Child site creation failed, sending error message and bailing');

            // log error
            error_log('Site Creation Error: ' . $site_id->get_error_message());

            // log details of error
            error_log('Details: ' . $site_id->get_error_data());

            wp_send_json_error('Unfortunately registration has failed. Our system has returned the following error: ' . $site_id->get_error_message() . ' The page will now reload so that you can try again. If the problem persists, and you think this is a mistake on our part, please contact us.');

            wp_die();
        }

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
        $plugins = array('woocommerce/woocommerce.php', 'woocommerce-payfast-gateway/gateway-payfast.php');

        foreach ($plugins as $plugin) {
            activate_plugin($plugin);
        }

        // Set Woocommerce country to ZA and currency to ZAR
        update_option('woocommerce_default_country', 'ZA');
        update_option('woocommerce_currency', 'ZAR');

        // Set Payfast settings
        update_option('woocommerce_payfast_settings', array(
            'enabled'      => 'yes',
            'title'        => 'Pay via Payfast',
            'description'  => 'You can pay via Payfast using your debit or credit card, or via instant EFT.',
            'testmode'     => 'no',
            'debug'        => 'yes',
            'merchant_id'  => '22846241',
            'merchant_key' => 'n3m666fmfyy8z',
            'pass_phrase'  => 'xHSSjLvPW67wPk',
            // SANDBOX SETTINGS
            // 'merchant_id'          => '10030856',
            // 'merchant_key'         => '33fgr2k1b7mef',
            // 'pass_phrase'           => 'xHSSjLvPW67wPk',
            'testmode'         => 'no',
            'send_debug_email' => 'yes',
            'debug_email'      => 'werner@silverbackdev.co.za',
            'enable_logging'   => 'yes',
        ));

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

        // if is wp error, send error message and bail
        if (is_wp_error($owner_id)) {

            // Log
            error_log('User creation failed, sending error message and bailing');

            // log error
            error_log('User Creation Error: ' . $owner_id->get_error_message());

            // log details of error
            error_log('Details: ' . $owner_id->get_error_data());

            wp_send_json_error('Unfortunately registration has failed. Our system has returned the following error: ' . $owner_id->get_error_message() . ' The page will now reload so that you can try again. If the problem persists, and you think this is a mistake on our part, please contact us.');

            wp_die();
        }

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
            $wp_new_user_notification_email['subject'] = 'Welcome to Excellerate Convenience, ' . $full_name . '!';

            // Customize the body of the email
            $wp_new_user_notification_email['message'] = '<h3>Hello ' . $full_name . '</h3>';
            $wp_new_user_notification_email['message'] .= '<p>Thank you for registering your shop on Excellerate Convenience.</p>';
            $wp_new_user_notification_email['message'] .= '<p>Your username is: <b>' . $owner_data->user_login . '</b></p>';
            $wp_new_user_notification_email['message'] .= "<p>Please visit <a href='https://www.excelleratetech.com/account-confirmation/?oid=$owner_id&akey=$acc_conf_nonce&sid=$site_id'><b>this page</b></a> to set your password and activate your account.</p>";
            $wp_new_user_notification_email['message'] .= '<p><b>Best regards,</b></p>';
            $wp_new_user_notification_email['message'] .= '<p><b>The Excellerate Convenience Team</b></p>';

            return $wp_new_user_notification_email;
        }, 10, 2);

        // Log
        error_log('Sending filtered new user notification email');

        // send user account notification
        try {
            wp_new_user_notification($owner_id, null, 'both');
            error_log('New user registration message sent successfully.');
        } catch (\Throwable $th) {
            error_log('Could not send new user notification: ' . $th->getMessage());
        }

        // remove custom headers filter after sending
        remove_filter('wp_mail_content_type', 'extech_reg_mail_content_type');

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
            array(
                'post_title'    => 'Orders',
                'post_content'  => '',
                'post_status'   => 'publish',
                'post_author'   => 1,
                'post_type'     => 'page',
                'post_parent'   => $parent_id,
                'page_template' => 'dashboard/page-dashboard-orders.php',   // Set the page template
                'post_name'     => 'shop-orders'
            ),
        );

        // Insert the child pages
        foreach ($child_pages as $child_page) {
            wp_insert_post($child_page);
        }

        // Retrieve WooCommerce shop page id and set as front page
        $shop_page_id       = get_option('woocommerce_shop_page_id');
        $shop_on_front      = update_option('page_on_front', $shop_page_id);
        $show_shop_on_front = update_option('show_on_front', 'page');

        if ($shop_on_front && $show_shop_on_front) :
            error_log('Shop page set as front page');
        else :
            error_log('Could not set shop page as front page');
        endif;

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
        error_log('Starting to upload and attach files to shop post');

        try {
            // ========================================================
            // loop through, upload and attach shop registration files
            // ========================================================

            // reg docs counter
            $reg_docs_count = 0;

            // loop
            foreach ($shop_bus_reg_docs as $doc) {

                // Log
                error_log('Uploading and attaching business registration document ' . $reg_docs_count + 1);

                // upload file
                $upload = wp_upload_bits($doc['name'], null, file_get_contents($doc['tmp_name']));

                // Log
                error_log('File upload response: ' . print_r($upload, true));

                // check upload
                if (!$upload['error']) {

                    // Log
                    error_log('File upload successful, attaching to post');

                    // add file as attachment
                    $attachment = array(
                        'post_mime_type' => $upload['type'],
                        'post_title'     => preg_replace('/\.[^.]+$/', '', $doc['name']),
                        'post_content'   => '',
                        'post_status'    => 'inherit',
                        'guid'           => $upload['url']
                    );

                    // insert attachment
                    $attach_id = wp_insert_attachment($attachment, $upload['file'], $post_id);

                    // acf row data
                    $row_data = [
                        'registration_doc' => $attach_id,
                    ];

                    // add row
                    add_row('business_registration_docs', $row_data, $post_id);

                    // Log
                    error_log('Attachment ID: ' . $attach_id);

                    // Log
                    error_log('Attaching file to post');

                    // attach file to post
                    $attach_data = wp_generate_attachment_metadata($attach_id, $upload['file']);
                    wp_update_attachment_metadata($attach_id, $attach_data);

                    // Log
                    error_log('File attached to post');

                    // increment reg docs counter
                    $reg_docs_count++;
                }
            }

            // =====================================================
            // loop through, upload and attach shop director files
            // =====================================================

            // director docs counter
            $director_docs_count = 0;

            // loop
            foreach ($shop_director_ids as $doc) {

                // Log
                error_log('Uploading and attaching director ID document ' . $director_docs_count + 1);

                // upload file
                $upload = wp_upload_bits($doc['name'], null, file_get_contents($doc['tmp_name']));

                // Log
                error_log('File upload response: ' . print_r($upload, true));

                // check upload
                if (!$upload['error']) {

                    // Log
                    error_log('File upload successful, attaching to post');

                    // add file as attachment
                    $attachment = array(
                        'post_mime_type' => $upload['type'],
                        'post_title'     => preg_replace('/\.[^.]+$/', '', $doc['name']),
                        'post_content'   => '',
                        'post_status'    => 'inherit',
                        'guid'           => $upload['url']
                    );

                    // insert attachment
                    $attach_id = wp_insert_attachment($attachment, $upload['file'], $post_id);

                    // acf row data
                    $row_data = [
                        'director_doc' => $attach_id,
                    ];

                    // add row
                    add_row('directors', $row_data, $post_id);

                    // Log
                    error_log('Attachment ID: ' . $attach_id);

                    // Log
                    error_log('Attaching file to post');

                    // attach file to post
                    $attach_data = wp_generate_attachment_metadata($attach_id, $upload['file']);
                    wp_update_attachment_metadata($attach_id, $attach_data);

                    // Log
                    error_log('File attached to post');

                    // increment director docs counter
                    $director_docs_count++;
                }
            }

            // ========================================================
            // loop through, upload and attach shop bank account files
            // ========================================================

            $bank_acc_docs_count = 0;

            // loop
            foreach ($shop_bank_acc as $doc) {

                // Log
                error_log('Uploading and attaching bank account document ' . $bank_acc_docs_count + 1);

                // upload file
                $upload = wp_upload_bits($doc['name'], null, file_get_contents($doc['tmp_name']));

                // Log
                error_log('File upload response: ' . print_r($upload, true));

                // check upload
                if (!$upload['error']) {

                    // Log
                    error_log('File upload successful, attaching to post');

                    // add file as attachment
                    $attachment = array(
                        'post_mime_type' => $upload['type'],
                        'post_title'     => preg_replace('/\.[^.]+$/', '', $doc['name']),
                        'post_content'   => '',
                        'post_status'    => 'inherit',
                        'guid'           => $upload['url']
                    );

                    // insert attachment
                    $attach_id = wp_insert_attachment($attachment, $upload['file'], $post_id);

                    // acf row data
                    $row_data = [
                        'bank_account_doc' => $attach_id,
                    ];

                    // add row
                    add_row('bank_account', $row_data, $post_id);

                    // Log
                    error_log('Attachment ID: ' . $attach_id);

                    // Log
                    error_log('Attaching file to post');

                    // attach file to post
                    $attach_data = wp_generate_attachment_metadata($attach_id, $upload['file']);
                    wp_update_attachment_metadata($attach_id, $attach_data);

                    // Log
                    error_log('File attached to post');

                    // increment bank acc docs counter
                    $bank_acc_docs_count++;
                }

            }
        } catch (\Throwable $th) {
            error_log('ERROR UPLOADING AND ATTACHING FILES: ' . $th->getMessage());
        }

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
        wp_send_json_success('Registration successful. An email has been sent to the email address supplied with instructions on how to set up your password.');

        // Log
        error_log('>>>>>>>>>>>>>>>>> ACCOUNT REGISTRATION END <<<<<<<<<<<<<<<<<');

    } catch (\Throwable $th) {
        
        // log
        error_log('SHOP REGISTRATION ERROR: ' . $th->getMessage());

        // log details of error
        error_log('Details: ' . $th->getTraceAsString());

        // send error message
        wp_send_json('SHOP REGISTRATION FAILED: '.$th->getTraceAsString());
    }

    wp_die();
}
