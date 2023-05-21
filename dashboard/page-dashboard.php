<?php

/**
 * Template Name: Dashboard
 */

get_header('dashboard');

global $post, $wpdb;

// retrieve shop id
$shop_id = fetch_shop_id();

// switch blog
switch_to_blog($shop_id);

// setup query to retrieve order data
$query = "
    SELECT 
        DATE(post_date) AS Date,
        SUM(meta.meta_value) AS QtySold,
        SUM(meta.meta_value * order_items.meta_value) AS TotalRevenue,
        AVG(order_items.meta_value) AS AvgSaleValue,
        MIN(order_items.meta_value) AS LowestSaleValue,
        MAX(order_items.meta_value) AS HighestSaleValue
    FROM {$wpdb->posts} AS posts
    INNER JOIN {$wpdb->postmeta} AS meta ON posts.ID = meta.post_id
    INNER JOIN {$wpdb->prefix}woocommerce_order_items AS order_items ON posts.ID = order_items.order_id
    INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS item_meta ON order_items.order_item_id = item_meta.order_item_id
    WHERE posts.post_type = 'shop_order'
        AND posts.post_status IN ('wc-completed', 'wc-processing')
        AND meta.meta_key = '_qty'
        AND item_meta.meta_key = '_line_total'
        AND posts.post_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)
    GROUP BY Date
    ORDER BY Date ASC
";

$results = $wpdb->get_results($query);

?>

<!-- Main content -->
<div id="dashboard-cont" class="container mt-5" style="min-height: 80vh;">
    <div class="row">
        <div id="sales-overview" class="col-md-12">

            <p class="bg-success-subtle text-center p-3 rounded-2 shadow-sm mb-4">
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
                    if (is_array($results) && !empty($results)) :

                        // Process the results as needed
                        foreach ($results as $result) :

                            // setup data
                            $date             = $result->Date;
                            $qtySold          = $result->QtySold;
                            $totalRevenue     = $result->TotalRevenue;
                            $avgSaleValue     = $result->AvgSaleValue;
                            $lowestSaleValue  = $result->LowestSaleValue;
                            $highestSaleValue = $result->HighestSaleValue; ?>

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