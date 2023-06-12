<?php


defined('ABSPATH') ?: exit();

add_action('wp_footer', function () { 
    
    // get current page slug
    $current_page_slug = get_post_field('post_name', get_post());

    // setup array of valid slug values
    $v_slugs = [
        'dashboard', 'shop-orders', 'qr-code', 'account', 'users', 'products'
    ];

    // if current page slug is not in valid slugs array, bail
    if (!in_array($current_page_slug, $v_slugs)) {
        return;
    }
    
    ?>

    <!-- bootstrap modal to be used for showing new order notifications -->
    

    <!-- add script to check for new orders every 30 seconds -->
    <script>

        jQuery(function($) {




            // define function to check for new orders
            function check_for_new_orders() {

                // get current blog id
                var blog_id = <?php echo get_current_blog_id(); ?>;

                // define ajax data
                var data = {
                    action: 'extech_check_for_new_orders',
                    nonce: '<?php echo wp_create_nonce('extech_check_for_new_orders'); ?>',
                    blog_id: blog_id,
                };

                // make ajax request
                $.post('<?php echo admin_url('admin-ajax.php'); ?>', data, function(response) {

                    // if response is not empty
                    if (response !== '') {

                        // parse response
                        // var response = JSON.parse(response);

                        console.log(response);
                       
                        // show notification
                        show_notification();

                        return;

                        // get new orders count
                        var new_orders_count = response.new_orders_count;

                        // if new orders count is greater than 0
                        if (new_orders_count > 0) {

                            // append badge to orders menu item
                            $('#new_orders').text(0);
                            $('#new_orders').text(new_orders_count);

                        }
                    }
                });
            }

            // call function to check for new orders every 30 seconds
            setInterval(check_for_new_orders, 10000);
        });
    </script>

<?php });

/**
 * Ajax action to check for new orders.
 */
add_action('wp_ajax_extech_check_for_new_orders', 'extech_check_for_new_orders');
add_action('wp_ajax_nopriv_extech_check_for_new_orders', 'extech_check_for_new_orders');

function extech_check_for_new_orders() {

    // check ajax nonce
    check_ajax_referer('extech_check_for_new_orders', 'nonce');

    // get blog id
    $blog_id = $_POST['blog_id'];

    // switch to blog
    switch_to_blog($blog_id);

    // get orders with a status other than cancelled or refunded or failed
    $orders = wc_get_orders([
        'status' => ['processing', 'on-hold'],
        'limit'  => -1
    ]);

    // if orders exist
    if ($orders) {

        // get order count
        $order_count = count($orders);

        // switch back to main site
        restore_current_blog();

        wp_send_json_success($order_count);
    } else {

        $order_count = 0;

        // switch back to main site
        restore_current_blog();

        wp_send_json_error($order_count);
    }
}

?>