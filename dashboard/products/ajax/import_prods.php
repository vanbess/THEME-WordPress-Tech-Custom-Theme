<?php

defined('ABSPATH') ?: exit();

/**
 * Import multiple prods AJAX
 */
add_action('wp_ajax_nopriv_extech_import_prods', 'extech_import_prods');
add_action('wp_ajax_extech_import_prods', 'extech_import_prods');

function extech_import_prods() {

    check_ajax_referer(IMPORT_PROD_NONCE);

    wp_send_json($_POST);

    wp_die();
}
