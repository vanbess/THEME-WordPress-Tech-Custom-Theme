<?php

/**
 * Template Name: Dashboard
 */

//  if user is not logged in, redirect to login page
if (!is_user_logged_in()) {
    wp_redirect(site_url('/dashboard/login'));
    exit;
}

get_header('dashboard');

global $post, $wpdb;

// retrieve shop id
$shop_id = fetch_shop_id();

// echo $shop_id;

// switch blog
// switch_to_blog($shop_id);

$query = "
SELECT 
DATE(posts.post_date) AS summary_date,
SUM(order_itemmeta_qty.meta_value) AS total_qty,
SUM(postmeta_total.meta_value) AS gross_revenue,
AVG(postmeta_total.meta_value) AS average_sale_value,
MIN(postmeta_total.meta_value) AS lowest_sale,
MAX(postmeta_total.meta_value) AS highest_sale
FROM {$wpdb->prefix}posts AS posts
INNER JOIN {$wpdb->prefix}woocommerce_order_items AS order_items ON order_items.order_id = posts.ID
INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS order_itemmeta_qty ON order_itemmeta_qty.order_item_id = order_items.order_item_id AND order_itemmeta_qty.meta_key = '_qty'
INNER JOIN {$wpdb->prefix}postmeta AS postmeta_total ON postmeta_total.post_id = posts.ID AND postmeta_total.meta_key = '_order_total'
WHERE 
posts.post_type = 'shop_order'
GROUP BY 
DATE(posts.post_date)
ORDER BY 
DATE(posts.post_date) ASC
";


// retrieve orders
$orders = $wpdb->get_results($query);

// var_dump($orders);

?>

<!-- Main content -->
<div id="dashboard-cont" class="container mt-5" style="min-height: 80vh;">
    <div class="row">
        <div id="sales-overview" class="col-md-12">

            <p class="bg-success-subtle text-center p-3 fw-semibold rounded-2 shadow-sm mb-4">
                <b>Welcome to your shop dashboard!</b><br>Below you will find a breakdown of your total sales for the last month. Note that days without sales are skipped. You can click on any of the table headings to sort the table according to the corresponding table column values.
            </p>

            <table id="table-sales" class="table table-bordered table-striped shadow-sm">
                <thead class="text-center bg-dark-subtle">
                    <tr>
                        <th scope="col" class="sales-table fw-semibold text-decoration-underline cursor-pointer" title="click to sort by date">Date</th>
                        <th scope="col" class="sales-table fw-semibold text-decoration-underline cursor-pointer" title="click to sort by total products sold">Products Sold</th>
                        <th scope="col" class="sales-table fw-semibold text-decoration-underline cursor-pointer" title="click to sort by gross revenue">Gross Revenue</th>
                        <th scope="col" class="sales-table fw-semibold text-decoration-underline cursor-pointer" title="click to sort by average sale value">Avg. Sale Value</th>
                        <th scope="col" class="sales-table fw-semibold text-decoration-underline cursor-pointer" title="click to sort by lowest sale">Lowest Sale</th>
                        <th scope="col" class="sales-table fw-semibold text-decoration-underline cursor-pointer" title="click to sort by highest sale">Highest Sale</th>
                    </tr>
                </thead>

                <tbody id="table-sales-body" class="text-center">

                    <?php
                    if (is_array($orders) && !empty($orders)) :

                        // Process the orderss as needed
                        foreach ($orderss as $orders) :

                            // setup data
                            $date             = $orders->Date;
                            $qtySold          = $orders->QtySold;
                            $totalRevenue     = $orders->TotalRevenue;
                            $avgSaleValue     = $orders->AvgSaleValue;
                            $lowestSaleValue  = $orders->LowestSaleValue;
                            $highestSaleValue = $orders->HighestSaleValue; ?>

                            <tr>
                                <td class="align-middle"><?php echo $date; ?></td>
                                <td class="align-middle"><?php echo $qtySold; ?></td>
                                <td class="align-middle"><?php echo $totalRevenue; ?></td>
                                <td class="align-middle"><?php echo $avgSaleValue; ?>.</td>
                                <td class="align-middle"><?php echo $lowestSaleValue; ?></td>
                                <td class="align-middle"><?php echo $highestSaleValue; ?></td>
                            </tr>

                        <?php endforeach;
                    else : ?>
                        <tr>
                            <td class="align-middle fw-semibold" colspan="6">There is currently no sales data to display. Once you start selling, sales data will be displayed here.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

        </div>
    </div>
</div>

<?php get_footer('dashboard'); ?>