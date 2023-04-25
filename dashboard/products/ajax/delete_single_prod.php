<?php

defined('ABSPATH') ?: exit();

/**
 * Delete single product AJAX
 */
add_action('wp_ajax_nopriv_extech_del_single_prod', 'extech_del_single_prod');
add_action('wp_ajax_extech_del_single_prod', 'extech_del_single_prod');

function extech_del_single_prod() {

    check_ajax_referer(DELETE_PROD_NONCE);

    wp_send_json($_POST);

    wp_die();
}
