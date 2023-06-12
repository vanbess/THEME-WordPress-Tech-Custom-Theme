<?php

/**
 * Template Name: Dashboard Orders Page
 */

get_header('dashboard');

global $post;

// get child site id from shop meta
$shop_meta = fetch_shop_meta();
$shop_id = $shop_meta['child_site_id'][0];

// switch to blog
switch_to_blog($shop_id);

// query orders
global $wpdb;

// Define the table names
$table_posts = $wpdb->posts;
$table_postmeta = $wpdb->postmeta;

// Define the meta keys for the desired fields
$meta_keys = array(
    '_billing_first_name',
    '_billing_last_name',
    '_billing_email',
    '_billing_phone',
    '_order_total',
);

// Prepare the query
$query = $wpdb->prepare("
    SELECT p.ID, 
           MAX(CASE WHEN pm.meta_key = %s THEN pm.meta_value END) AS first_name,
           MAX(CASE WHEN pm.meta_key = %s THEN pm.meta_value END) AS last_name,
           MAX(CASE WHEN pm.meta_key = %s THEN pm.meta_value END) AS email,
           MAX(CASE WHEN pm.meta_key = %s THEN pm.meta_value END) AS phone,
           MAX(CASE WHEN pm.meta_key = %s THEN pm.meta_value END) AS total
    FROM $table_posts p
    LEFT JOIN $table_postmeta pm ON p.ID = pm.post_id
    WHERE p.post_type = 'shop_order'
    GROUP BY p.ID
    ORDER BY p.ID DESC
", $meta_keys[0], $meta_keys[1], $meta_keys[2], $meta_keys[3], $meta_keys[4]);

// return orders
$orders = $wpdb->get_results($query);

?>

<!-- Main content -->
<div id="dashboard-cont" class="container pt-5" style="min-height: 90vh;">
    <div class="row orders">
        <div class="col-md-12">

            <p class="bg-success-subtle p-2 rounded-2 shadow-sm fw-semibold text-center mb-4">The table below contains a list of recently received orders.</p>

            <table class="table table-bordered table-striped">

                <!-- order table-->
                <thead class="bg-dark-subtle text-center rounded-1 mb-2">
                    <tr>
                        <th scope="col" class="order-table-th fw-semibold text-decoration-underline cursor-pointer" title="click to sort by order ID">Order ID</th>
                        <th scope="col" class="order-table-th fw-semibold text-decoration-underline cursor-pointer" title="click to sort by order customer name">Customer</th>
                        <th scope="col" class="order-table-th fw-semibold text-decoration-underline cursor-pointer" title="click to sort by customer email address">Email Address</th>
                        <th scope="col" class="order-table-th fw-semibold">Phone Number</th>
                        <th scope="col" class="order-table-th fw-semibold text-decoration-underline cursor-pointer" title="click to sort by order total">Total</th>
                        <th scope="col" class="order-table-th fw-semibold text-decoration-underline cursor-pointer" title="click to sort by order status">Order Status</th>
                        <th scope="col" class="order-table-th fw-semibold">View Items</th>
                        <th scope="col" class="order-table-th fw-semibold">Mark Complete</th>
                    </tr>
                </thead>

                <tbody id="order-list-body" class="text-center">

                    <?php if (empty($orders)) : ?>
                        <tr class="align-bottom">
                            <td colspan="7" class="bg-warning-subtle fw-semibold">
                                There are currently no orders to display. Once you start recieving orders, they will display here.
                            </td>
                        </tr>
                    <?php else : ?>

                        <?php foreach ($orders as $order) : ?>
                            <tr class="align-bottom">
                                <td class="align-middle"><?php echo $order->ID; ?></td>
                                <td class="align-middle"><?php echo $order->first_name . ' ' . $order->last_name; ?></td>
                                <td class="align-middle"><?php echo $order->email; ?></td>
                                <td class="align-middle"><?php echo $order->phone; ?></td>
                                <td class="align-middle"><?php echo $order->total; ?></td>
                                <td class="align-middle">
                                    <?php
                                    // get order status from order id
                                    $order_status = get_post_status($order->ID);
                                    // echo Woocommerce order status
                                    echo wc_get_order_status_name($order_status);
                                    ?>
                                </td>
                                <td class="align-middle">
                                    <button class="btn btn-primary" title="click to view order items" onclick="viewOrderItems(event, '<?php echo $order->ID; ?>')">View Items</button>
                                </td>
                                <td class="align-middle">
                                    <!-- if order status is not yet complete, add button to mark complete, else add same button but disable it -->
                                    <?php if ($order_status != 'wc-completed') : ?>
                                        <button class="btn btn-secondary" title="click to mark order completed" onclick="markComplete(event, '<?php echo $order->ID; ?>')">Mark Complete</button>
                                    <?php else : ?>
                                        <button class="btn btn-success bg-success-subtle text-black-50 fw-semibold" disabled>Marked Complete</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                    <?php endif; ?>
                </tbody>

            </table>

        </div>

        <!-- order line items modal overlay -->
        <div id="order_line_items_modal_overlay" class="d-none position-fixed"></div>

        <!-- order line items modal -->
        <div id="order_line_items_modal" class="d-none position-absolute pt-4 pe-4 pb-4 ps-4 bg-white text-text-center w-50 rounded-1">

            <!-- title -->
            <div class="row">
                <div class="col-md-12">

                    <!-- close modal button -->
                    <button type="button" class="btn-close float-end" aria-label="Close" onclick="closeModal(event)"></button>

                    <h3 class="text-center pb-4">Order Line Items</h3>

                    <hr class="mt-0 mb-4">

                    <!-- line items table -->
                    <table class="table table-bordered table-striped">

                        <!-- line items table header -->
                        <thead class="bg-dark-subtle text-center rounded-1 mb-2">
                            <tr>
                                <th scope="col" class="fw-semibold">Product ID</th>
                                <th scope="col" class="fw-semibold">Image</th>
                                <th scope="col" class="fw-semibold">Product Name</th>
                                <th scope="col" class="fw-semibold">Quantity</th>
                                <th scope="col" class="fw-semibold">Price</th>
                                <th scope="col" class="fw-semibold">Total</th>
                            </tr>
                        </thead>

                        <!-- line items table body -->
                        <tbody id="order_line_items_body" class="text-center">

                        <tbody>

                    </table>

                </div>
            </div>

        </div>

    </div>
</div>

<?php

// echo $shop_meta['child_site_id'][0];

// echo '<pre>';
// print_r($shop_meta);
// echo '</pre>';


?>

<script>
    $ = jQuery;

    // View order items
    function viewOrderItems(event, order_id) {

        event.preventDefault();

        // get order line items using standard wordpress ajax
        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: {
                action: 'extech_get_order_line_items',
                nonce: '<?php echo wp_create_nonce('extech_get_order_line_items'); ?>',
                order_id: order_id,
                blog_id: '<?php echo $shop_id; ?>',
            },
            success: function(data) {

                var order_line_items = data;

                // console.log(order_line_items);

                // return;

                // define order line items table body
                var order_line_items_body = '';

                // loop through order line items
                order_line_items.forEach(function(order_line_item) {

                    // console.log(order_line_item);

                    // define order line item product id
                    var order_line_item_product_id = order_line_item.product_id;

                    // define order line item product image
                    var order_line_item_product_image = order_line_item.product_img;

                    // define order line item product name
                    var order_line_item_product_name = order_line_item.product_name;

                    // define order line item quantity
                    var order_line_item_quantity = order_line_item.quantity;

                    // define order line item price
                    var order_line_item_price = order_line_item.price;

                    // define order line item total
                    var order_line_item_total = order_line_item.total;

                    // define order line item row
                    var order_line_item_row = '<tr>' +
                        '<td class="align-middle">' + order_line_item_product_id + '</td>' +
                        '<td class="align-middle"><img src="' + order_line_item_product_image + '" alt="' + order_line_item_product_name + '" width="80" height="80"></td>' +
                        '<td class="align-middle">' + order_line_item_product_name + '</td>' +
                        '<td class="align-middle">' + order_line_item_quantity + '</td>' +
                        '<td class="align-middle">' + order_line_item_price + '</td>' +
                        '<td class="align-middle">' + order_line_item_total + '</td>' +
                        '</tr>';

                    // append order line item row to order line items body
                    order_line_items_body += order_line_item_row;

                });

                // append order line items body to order line items table body
                $('#order_line_items_body').html(order_line_items_body);

            },
            error: function(error) {
                console.log(error);
            }
        });

        // show modal
        $('#order_line_items_modal').removeClass('d-none').addClass('d-block');

        // show modal overlay
        $('#order_line_items_modal_overlay').removeClass('d-none').addClass('d-block');

    }

    // Close modal
    function closeModal(event) {

        event.preventDefault();

        // hide modal
        $('#order_line_items_modal').removeClass('d-block').addClass('d-none');

        // hide modal overlay
        $('#order_line_items_modal_overlay').removeClass('d-block').addClass('d-none');

    }

    // Mark order complete
    function markComplete(event, order_id) {

        event.preventDefault();

        // change button text
        $(event.target).text('Marking Complete...');

        // get order line items using standard wordpress ajax
        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: {
                action: 'extech_mark_order_complete',
                nonce: '<?php echo wp_create_nonce('extech_mark_order_complete'); ?>',
                order_id: order_id,
                blog_id: '<?php echo $shop_id; ?>',
            },
            success: function(data) {
               
                if(data.success){
                    alert(data.data);
                }else{
                    alert(data.data);
                }

                // reload page
                location.reload();

            },
            error: function(error) {
                console.log(error);
            }
        });

    }
</script>

<?php get_footer('dashboard');
