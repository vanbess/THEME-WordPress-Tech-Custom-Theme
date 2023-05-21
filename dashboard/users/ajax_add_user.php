<?php

defined('ABSPATH') ?: exit();

/**
 * AJAX to add new user to dashboard
 */
add_action('wp_ajax_nopriv_extech_add_new_user', 'extech_add_new_user');
add_action('wp_ajax_extech_add_new_user', 'extech_add_new_user');

function extech_add_new_user() {

    check_ajax_referer('extech add new user');

    // Fetch variables
    $firstName = $_POST['firstName'];
    $lastName  = $_POST['lastName'];
    $email     = $_POST['email'];
    $pass      = $_POST['passConf'];
    $role      = $_POST['role'];
    $site_id   = $_POST['site_id'];

    // determine role and add if is shop attendant
    if ($role === 'manager') :
        $role = 'shop_manager';
    else :
        $role = 'petrol_attendant';
    endif;

    // switch to correct site context
    switch_to_blog($site_id);

    // insert user
    $user_inserted = wp_insert_user([
        'user_pass'     => $pass,
        'user_login'    => $email,
        'user_nicename' => $firstName,
        'user_email'    => $email,
        'first_name'    => $firstName,
        'last_name'     => $lastName,
        'role'          => $role
    ]);

    // handle error/success
    if (is_wp_error($user_inserted)) :
        error_log('[SUBSITE USER INSERTION] Could not insert user. Reason: ' . $user_inserted->get_error_message());
        $errMsg = 'Could not insert user. Reason: ' . $user_inserted->get_error_message();
    else :
        $successMsg = 'User successfully added with user ID ' . $user_inserted;
    endif;

    // switch back to default site id
    switch_to_blog(1);

    // return json response
    if ($errMsg) :
        wp_send_json_error($errMsg);
    else :
        wp_send_json_success($successMsg);
    endif;

    wp_die();
}
