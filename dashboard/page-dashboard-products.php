<?php

/**
 * Template Name: Dashboard Products Page
 */

get_header('dashboard');

global $post;

?>

<!-- Main content -->
<div id="dashboard-cont" class="container mt-5" style="min-height: 90vh;">
    <div class="row products">
        <div class="col-12">

            <div class="container mt-5 mb-5">

                <!-- Nav tabs -->
                <ul class="nav nav-tabs" id="ordersTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active fw-semibold" id="add-product-tab" data-bs-toggle="tab" data-bs-target="#add-product" type="button" role="tab" aria-controls="add-product" aria-selected="true">Add Product</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-semibold" id="import-products-tab" data-bs-toggle="tab" data-bs-target="#import-products" type="button" role="tab" aria-controls="import-products" aria-selected="false">Import Products</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-semibold" id="all-products-tab" data-bs-toggle="tab" data-bs-target="#all-products" type="button" role="tab" aria-controls="all-products" aria-selected="false">All Products</button>
                    </li>
                </ul>
                <?php
                // if edit prod request
                if (isset($_GET['edit_prod'])) : ?>

                    <div id="edit_prod" class="mb-5 pb-5">
                        <?php include __DIR__ . '/products/edit_product.php'; ?>
                    </div>

                <?php
                // if standard request
                else : ?>

                    <!-- Tab panes -->
                    <div class="tab-content p-5 mb-5">
                        <div class="tab-pane active" id="add-product" role="tabpanel" aria-labelledby="add-product-tab">
                            <?php include __DIR__ . '/products/add_product.php' ?>
                        </div>

                        <div class="tab-pane" id="import-products" role="tabpanel" aria-labelledby="import-products-tab">
                            <?php include __DIR__ . '/products/import_products.php' ?>
                        </div>

                        <div class="tab-pane" id="all-products" role="tabpanel" aria-labelledby="all-products-tab">
                            <?php include __DIR__ . '/products/product_table.php' ?>
                        </div>
                    </div>

                <?php endif; ?>

            </div>

        </div>
    </div>
</div>

<script>
    $ = jQuery;

    // load add product tab pane on page load
    $(document).ready(function() {
        $('#add-product-tab').click();
    });

    // hide/show bootstrap tabs on click
    $('#ordersTab a').on('click', function(e) {

        e.preventDefault()

        // hide all tabs
        $('#ordersTab a').removeClass('active');

        // show clicked tab
        $(this).tab('show')

        // add active class to clicked tab
        $(this).addClass('active');

        // hide all tab panes
        $('.tab-pane').removeClass('active');

        // show clicked tab pane
        $($(this).attr('href')).addClass('active');

    })
</script>

<?php get_footer('dashboard'); ?>