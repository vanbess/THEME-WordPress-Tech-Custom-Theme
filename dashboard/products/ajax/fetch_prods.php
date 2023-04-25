<?php

defined('ABSPATH') ?: exit();

/**
 * AJAX to fetch and return initial list of products (50 limit)
 */
add_action('wp_ajax_nopriv_extech_fetch_prods', 'extech_fetch_prods');
add_action('wp_ajax_extech_fetch_prods', 'extech_fetch_prods');

function extech_fetch_prods() {

    check_ajax_referer('fetch prods nonce');

    // switch to correct site context
    $site_id = $_POST['site_id'];
    switch_to_blog($site_id);

    // if currency and location is not yet set, set it now (fallback for shops previously not registered as SA shops with ZAR currency)
    if(get_option('woocommerce_currency') !== 'ZAR'):
        update_option( 'woocommerce_currency', 'ZAR' );
    endif;

    if(get_option('woocommerce_default_country') !== 'ZA'):
        update_option( 'woocommerce_default_country', 'ZA' );
    endif;

    // set up page
    $paged = intval($_POST['page']);

    // fetch products
    $prods = new WP_Query([
        'post_type'      => 'product',
        'posts_per_page' => 50,
        'paged'          => $paged
    ]);

    // set up return data array
    $prod_return_data = [];

    // if prods found, push relevant data to $prod_return_data
    if ($prods->have_posts()) :

        while ($prods->have_posts()) : $prods->the_post();
            $prod_return_data[get_the_ID()] = [
                'img_url' => esc_url(get_the_post_thumbnail_url(get_the_ID())),
                'title'   => esc_attr(get_the_title(get_the_ID())),
                'sku'     => esc_attr(get_post_meta(get_the_ID(), '_sku', true)),
                'price'   => get_post_meta(get_the_ID(), '_sale_price', true) ? get_post_meta(get_the_ID(), '_sale_price', true) : get_post_meta(get_the_ID(), '_regular_price', true),
                'soh'     => intval(get_post_meta(get_the_ID(), '_stock', true)),
                'currency' => get_woocommerce_currency()
            ];
        endwhile;

        wp_reset_postdata();

        // return $prod_return_data
        wp_send_json_success($prod_return_data);

    // if no prods found
    else :
        wp_send_json_error(['message' => 'Your store does not currently have any published products. Please click on the Add Product or Import Products tab to begin adding products to your store.']);
    endif;


    // return to main site context
    restore_current_blog();

    wp_die();
}
