<?php

defined('ABSPATH')?: exit();

add_action('wp_ajax_extech_get_order_line_items', 'extech_get_order_line_items');
add_action('wp_ajax_nopriv_extech_get_order_line_items', 'extech_get_order_line_items');

function extech_get_order_line_items() {

    // check ajax nonce
    check_ajax_referer('extech_get_order_line_items', 'nonce');

    try {
        // get blog id
        $blog_id = $_POST['blog_id'];

        // switch to blog
        switch_to_blog($blog_id);

        // get order id
        $order_id = $_POST['order_id'];

        // get order
        $order = wc_get_order($order_id);

        // get order line items
        $order_line_items = $order->get_items();

        // define order line items array
        $order_line_items_array = [];

        // loop through order line items
        foreach ($order_line_items as $order_line_item) {

            // get item object
            $item = $order_line_item->get_data();

            // get order line item product id
            $order_line_item_product_id = $item['product_id'];

            // get product image
            $product_image = get_the_post_thumbnail_url($order_line_item_product_id);

            // get order line item product name
            $order_line_item_product_name = $item['name'];

            // get order line item quantity
            $order_line_item_quantity = $item['quantity'];

            // get order line item price
            $order_line_item_price = $item['subtotal'];

            // get order line item total
            $order_line_item_total = $item['total'];

            // add order line item to order line items array
            array_push($order_line_items_array, [
                'product_id'   => $order_line_item_product_id,
                'product_img'  => $product_image,
                'product_name' => $order_line_item_product_name,
                'quantity'     => $order_line_item_quantity,
                'price'        => $order_line_item_price,
                'total'        => $order_line_item_total,
            ]);
        }

        // return order line items array
        wp_send_json($order_line_items_array);
    } catch (\Throwable $th) {
        // send error message via wp_die()
        wp_send_json_error($th->getMessage());
    }
}


?>