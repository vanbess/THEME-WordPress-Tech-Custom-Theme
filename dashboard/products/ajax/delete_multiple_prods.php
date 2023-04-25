<?php

defined('ABSPATH') ?: exit();

/**
 * Delete multiple products AJAX
 */
add_action('wp_ajax_nopriv_extech_del_multi_prods', 'extech_del_multi_prods');
add_action('wp_ajax_extech_del_multi_prods', 'extech_del_multi_prods');

function extech_del_multi_prods() {

    check_ajax_referer('DELETE_PRODS_NONCE');

    wp_send_json($_POST);

    wp_die();
}
