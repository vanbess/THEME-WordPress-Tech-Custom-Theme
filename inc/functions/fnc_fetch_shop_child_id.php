<?php

defined('ABSPATH') ?: exit();

/**
 * Fetch shop network ID
 *
 * @return int shop network id
 */
function fetch_shop_child_site_id() {

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
        return false;
    else :

        // get shop id
        return intval($shops->posts[0]);

    endif;
}
