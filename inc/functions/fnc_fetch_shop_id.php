<?php

defined('ABSPATH') ?: exit();

/**
 * Fetches and returns shop id
 *
 * @return int $shop_id
 */
function fetch_shop_id() {

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

        // get shop post id
        $shop_post_id = $shops->posts[0];

        // get and return actual shop id
        return $shop_id =  get_post_meta($shop_post_id, 'child_site_id', true);

    endif;
}
