<?php

defined('ABSPATH') ?: exit();

/**
 * Delete single product AJAX
 */
add_action('wp_ajax_nopriv_extech_del_single_prod', 'extech_del_single_prod');
add_action('wp_ajax_extech_del_single_prod', 'extech_del_single_prod');

function extech_del_single_prod() {

    check_ajax_referer('delete prod nonce');

    $deleted = wp_delete_post($_POST['pid'], true);

    if (!$deleted || is_null($deleted)) :
        wp_send_json_error(['msg' => 'Product could not be deleted due to an unkwown reason. If the product still remains in your product list, please contact us so that we can assist.']);
    else :
        wp_send_json_success(['msg' => 'Product was successfully deleted.']);
    endif;

    wp_die();
}
