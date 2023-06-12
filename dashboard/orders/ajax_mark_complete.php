<?php
defined('ABSPATH')?: exit();

add_action('wp_ajax_extech_mark_order_complete', 'extech_mark_order_complete');
add_action('wp_ajax_nopriv_extech_mark_order_complete', 'extech_mark_order_complete');

function extech_mark_order_complete()
{

    // verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'extech_mark_order_complete')) {
        die();
    }

    // get order id
    $order_id = $_POST['order_id'];

    // get blog id
    $blog_id = $_POST['blog_id'];

    // switch to blog
    switch_to_blog($blog_id);

    // mark order as complete
    $order   = wc_get_order($order_id);
    $updated = $order->update_status('completed');

    // if order status updated
    if ($updated) {

        // switch back to main site
        restore_current_blog();

        wp_send_json_success('Order marked as complete.');

    } else {

        // switch back to main site
        restore_current_blog();

        wp_send_json_error('Order could not be marked as complete. Please reload the page and try again.');
       
    }

}
?>