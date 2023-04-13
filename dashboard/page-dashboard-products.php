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

            <div class="container mt-5">

                <!-- Nav tabs -->
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="add-product-tab" data-bs-toggle="tab" data-bs-target="#add-product" type="button" role="tab" aria-controls="add-product" aria-selected="true">Add Product</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="import-products-tab" data-bs-toggle="tab" data-bs-target="#import-products" type="button" role="tab" aria-controls="import-products" aria-selected="false">Import Products</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="all-products-tab" data-bs-toggle="tab" data-bs-target="#all-products" type="button" role="tab" aria-controls="all-products" aria-selected="false">All Products</button>
                    </li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content" id="myTabContent">

                    <!-- Add Product Form -->
                    <div class="tab-pane fade show active" id="add-product" role="tabpanel" aria-labelledby="add-product-tab">
                        <?php include __DIR__ . '/products/add_product.php' ?>
                    </div>

                    <!-- Import Products Form -->
                    <div class="tab-pane fade" id="import-products" role="tabpanel" aria-labelledby="import-products-tab">
                        <?php include __DIR__ . '/products/import_products.php' ?>
                    </div>

                    <!-- All Products Table -->
                    <div class="tab-pane fade" id="all-products" role="tabpanel" aria-labelledby="all-products-tab">
                        <?php include __DIR__ . '/products/product_table.php' ?>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

<?php get_footer('dashboard'); ?>