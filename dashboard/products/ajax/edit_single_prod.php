<?php

defined('ABSPATH') ?: exit();

/**
 * Edit single product AJAX
 */
add_action('wp_ajax_nopriv_extech_edit_single_prod', 'extech_edit_single_prod');
add_action('wp_ajax_extech_edit_single_prod', 'extech_edit_single_prod');

function extech_edit_single_prod() {

    check_ajax_referer(EDIT_PROD_NONCE);

    wp_send_json($_POST);

    wp_die();
}
