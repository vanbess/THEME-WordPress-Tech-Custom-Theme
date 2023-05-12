<?php

/**
 * Template Name: Dashboard
 */

get_header('dashboard');

global $post;

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
                    <tr>
                        <td class="align-middle">fddsfdsf</td>
                        <td class="align-middle">sfsdfsdfsdf</td>
                        <td class="align-middle">sfddsfsdf</td>
                        <td class="align-middle">sfddsfsfd</td>
                        <td class="align-middle">sfddsffsd</td>
                        <td class="align-middle">sfdsdfsdfds</td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>
</div>

<?php get_footer('dashboard'); ?>