<?php

defined('ABSPATH') ?: exit();

/**
 * Show/load more products AJAX
 */
add_action('wp_ajax_nopriv_extech_show_more_prods', 'extech_show_more_prods');
add_action('wp_ajax_extech_show_more_prods', 'extech_show_more_prods');

function extech_show_more_prods() {

    check_ajax_referer(MORE_PROD_NONCE);

    wp_send_json($_POST);

    wp_die();
}
