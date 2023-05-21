<?php

use Elementor\Modules\System_Info\Reporters\User;

defined('ABSPATH') ?: exit();

/**
 * AJAX action to delete user
 */

add_action('wp_ajax_nopriv_extech_del_user', 'extech_del_user');
add_action('wp_ajax_extech_del_user', 'extech_del_user');

function extech_del_user() {

    global $wpdb;

    check_ajax_referer('extech del user');

    // switch to correct site context
    switch_to_blog($_POST['site_id']);

    // delete user and associated meta as needed
    $userMetaDeleted = $wpdb->delete($wpdb->usermeta, ['user_id' => $_POST['user_id']]);
    $userDeleted     = $wpdb->delete($wpdb->users, ['ID' => $_POST['user_id']]);

    // switch back to main site context
    switch_to_blog(1);

    // return error/success
    if ($userMetaDeleted && $userDeleted) :
        wp_send_json_success('User successfully deleted.');
    else :
        wp_send_json_error('Could not delete the user. Please reload the page and try again.');
    endif;
}
