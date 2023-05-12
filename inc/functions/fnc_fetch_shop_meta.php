<?php

defined('ABSPATH') ?: exit();

/**
 * Fetches and returns shop meta or false on failure
 *
 * @return void
 */
function fetch_shop_meta() {

    // return error message
    $err_msg = false;

    // get current site url
    $curr_site_url = get_bloginfo('url');

    // switch to main
    switch_to_blog(1);

    // setup query
    $shops = new WP_Query([
        'post_type'      => 'shop',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'meta_key'       => 'shop_url',
        'meta_value'     => $curr_site_url,
        'fields'         => 'ids'
    ]);

    // check returned posts (if any)
    if (empty($shops->posts)) :
        return $err_msg = true;
    else :

        // get shop id
        $shop_id = $shops->posts[0];

        // get shop meta
       return get_post_meta($shop_id);

    endif;
}
