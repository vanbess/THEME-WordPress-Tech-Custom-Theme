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

$query = "SELECT p.ID AS order_id,
           pm1.meta_value AS first_name,
           pm2.meta_value AS last_name,
           pm3.meta_value AS email,
           pm4.meta_value AS phone_number,
           pm5.meta_value AS order_total,
           pm6.meta_value AS order_status,
           GROUP_CONCAT(DISTINCT CONCAT(pm7.meta_value, ' x ', pm8.meta_value) SEPARATOR ', ') AS order_items
    FROM {$wpdb->prefix}posts AS p
    INNER JOIN {$wpdb->prefix}postmeta AS pm1 ON (p.ID = pm1.post_id AND pm1.meta_key = '_billing_first_name')
    INNER JOIN {$wpdb->prefix}postmeta AS pm2 ON (p.ID = pm2.post_id AND pm2.meta_key = '_billing_last_name')
    INNER JOIN {$wpdb->prefix}postmeta AS pm3 ON (p.ID = pm3.post_id AND pm3.meta_key = '_billing_email')
    INNER JOIN {$wpdb->prefix}postmeta AS pm4 ON (p.ID = pm4.post_id AND pm4.meta_key = '_billing_phone')
    INNER JOIN {$wpdb->prefix}postmeta AS pm5 ON (p.ID = pm5.post_id AND pm5.meta_key = '_order_total')
    INNER JOIN {$wpdb->prefix}postmeta AS pm6 ON (p.ID = pm6.post_id AND pm6.meta_key = '_order_status')
    INNER JOIN {$wpdb->prefix}woocommerce_order_items AS oi ON (p.ID = oi.order_id)
    INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS oim1 ON (oi.order_item_id = oim1.order_item_id AND oim1.meta_key = '_product_id')
    INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS oim2 ON (oi.order_item_id = oim2.order_item_id AND oim2.meta_key = '_qty')
    INNER JOIN {$wpdb->prefix}postmeta AS pm7 ON (oim1.meta_value = pm7.post_id AND pm7.meta_key = '_sku')
    INNER JOIN {$wpdb->prefix}postmeta AS pm8 ON (oim2.order_item_id = pm8.post_id AND pm8.meta_key = '_qty')
    WHERE p.post_type = 'shop_order'
    GROUP BY p.ID
    ORDER BY p.post_date DESC";


$orders = $wpdb->get_results($query);

// echo '<pre>';
// print_r($orders);
// echo '</pre>';


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
                                <td class="align-middle"></td>
                                <td class="align-middle"></td>
                                <td class="align-middle"></td>
                                <td class="align-middle"></td>
                                <td class="align-middle"></td>
                                <td class="align-middle"></td>
                                <td class="align-middle">
                                    <button class="btn btn-primary" onclick="viewOrderItems(event)">View Items</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                    <?php endif; ?>
                </tbody>

            </table>

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
    function viewOrderItems(event) {

        event.preventDefault();

    }
</script>

<style>

</style>

<?php get_footer('dashboard');
