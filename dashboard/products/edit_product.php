<p class="bg-success-subtle fw-semibold text-center p-3 rounded-3 mb-4 shadow-sm">
    Use the inputs below to edit your product. All fields marked with an asterisk (*) are required.
</p>

<?php

// get prod id
$prod_id = $_GET['edit_prod'];

// get product object
$prod_obj = wc_get_product($prod_id);

?>

<form id="form_edit_single_prod" action="" class="mb-5 pb-5">

    <!-- Product title -->
    <div class="mb-3">
        <label for="product_title" class="form-label fw-semibold">Product Title *</label>
        <small class="ms-2 form-text text-muted font-italic">the title of this product</small>
        <input type="text" class="form-control shadow-sm" id="product_title" value="<?php echo $prod_obj->get_title(); ?>">
    </div>

    <!-- Product SKU -->
    <div class="mb-3">
        <label for="product_sku" class="form-label fw-semibold">Product SKU *</label>
        <small class="ms-2 form-text text-muted font-italic">the stock keeping unit this product</small>
        <input type="text" class="form-control shadow-sm" id="product_sku" value="<?php echo $prod_obj->get_sku(); ?>">
    </div>

    <!-- Product Regular Price -->
    <div class="mb-3">
        <label for="product_regular_price" class="form-label fw-semibold">Product Regular Price *</label>
        <small class="ms-2 form-text text-muted font-italic">the regular price for this product</small>
        <input type="number" step="0.01" class="form-control shadow-sm" id="product_regular_price" value="<?php echo $prod_obj->get_regular_price(); ?>">
    </div>

    <!-- Product Sale Price -->
    <div class="mb-3">
        <label for="product_sale_price" class="form-label fw-semibold">Product Sale Price</label>
        <small class="ms-2 form-text text-muted font-italic">the sale price for this product (optional - overrides regular price)</small>
        <input type="number" step="0.01" class="form-control shadow-sm" id="product_sale_price" value="<?php echo $prod_obj->get_sale_price(); ?>">
    </div>

    <!-- Product Description -->
    <div class="mb-3">
        <label for="product_description" class="form-label fw-semibold">Product Description *</label>
        <small class="ms-2 form-text text-muted font-italic">a short description for this product</small>
        <textarea class="form-control shadow-sm" id="product_description" rows="4" maxlength="250"><?php echo $prod_obj->get_description(); ?></textarea>
    </div>

    <!-- Stock on hand -->
    <div class="mb-3">
        <label for="product_stock" class="form-label fw-semibold">Stock on hand *</label>
        <small class="ms-2 form-text text-muted font-italic">current stock on hand for this product</small>
        <input type="number" id="product_stock" class="form-control shadow-sm" step="1" min="1" value="<?php echo $prod_obj->get_stock_quantity(); ?>">
    </div>

    <!-- Product Image -->
    <div class="mb-3">
        <label for="product_image" class="form-label fw-semibold">Product Image</label>
        <small class="ms-2 form-text text-muted font-italic">image for this product (optional but recommended)</small>
        <input type="file" class="form-control shadow-sm" id="product_image">

        <p class="form-label fw-semibold pt-4"><u>Current image:</u></p>

        <div id="remove_img_cont" class="position-relative">

            <a id="remove_img" class="btn btn-danger position-absolute shadow-lg d-none" title="click to delete" href="#">x</a>

            <?php
            $img_id = $prod_obj->get_image_id();
            $img_src = wp_get_attachment_image_src($img_id, 'woocommerce-thumbnail');
            ?>

            <img id="edit_prod_img" src="<?php echo $img_src[0]; ?>" alt="<?php echo $prod_obj->get_title; ?>">

        </div>

        <script>
            jQuery(document).ready(function($) {

                // get img width
                var img_width = $('#edit_prod_img').width();

                // set container width to img width
                $('#remove_img_cont').width(img_width);

                // container on mouse enter
                $('#remove_img_cont').mouseenter(function() {
                    $('#edit_prod_img').addClass('opacity-50');
                    $('#remove_img').removeClass('d-none');
                });

                // container on mouse leave
                $('#remove_img_cont').mouseleave(function() {
                    $('#edit_prod_img').removeClass('opacity-50');
                    $('#remove_img').addClass('d-none');
                });
            });
        </script>

    </div>

    <!-- Publish/Unpublish Product -->
    <div class="mb-4">
        <label for="product_status" class="form-label fw-semibold">Product Status *</label>
        <small class="ms-2 form-text text-muted font-italic">the status of this product (publish = visible in shop, draft = hidden from shop)</small>
        <select class="form-control shadow-sm" id="product_status">
            <option value="" disabled selected>Select product status</option>
            <option value="publish" <?php echo $prod_obj->get_status() === 'publish' ? 'selected' : ''; ?>>Publish</option>
            <option value="draft" <?php echo $prod_obj->get_status() === 'draft' ? 'selected' : ''; ?>>Draft</option>
        </select>
    </div>

    <!-- Submit Button -->
    <button id="update_single_product" type="submit" class="btn btn-primary btn-lg w-100 shadow-sm">Update Product</button>

</form>

<?php
add_action('wp_footer', function () {

    // retrieve blog_id
    global $blog_id;

?>

    <script id="sub_single_prod">
        jQuery(document).ready(function($) {

            $("#form_edit_single_prod").submit(function(e) {

                $('#update_single_product').text('Working...');

                e.preventDefault();

                // Check if required fields are filled in
                if ($("#product_title").val() === '') {
                    alert("Please enter a product title.");
                    return;
                }
                if ($("#product_sku").val() === '') {
                    alert("Please enter a product SKU.");
                    return;
                }
                if ($("#product_regular_price").val() === '') {
                    alert("Please enter a product regular price.");
                    return;
                }
                if ($("#product_description").val() === '') {
                    alert("Please enter a product description.");
                    return;
                }
                if ($("#product_status").val() === '') {
                    alert("Please select a product status.");
                    return;
                }
                if ($("#product_status").val() === '') {
                    alert("Please select a product status.");
                    return;
                }
                if ($("#product_stock").val() === '') {
                    alert("Please provide stock on hand for this product.");
                    return;
                }

                var formData = new FormData();
                formData.append("product_title", $("#product_title").val());
                formData.append("product_sku", $("#product_sku").val());
                formData.append("product_regular_price", $("#product_regular_price").val());
                formData.append("product_sale_price", $("#product_sale_price").val());
                formData.append("product_description", $("#product_description").val());
                formData.append("product_image", $("#product_image").prop("files")[0]);
                formData.append("product_status", $("#product_status").val());
                formData.append("product_stock", $("#product_stock").val());
                formData.append("current_blog_id", '<?php echo $blog_id ?>');
                formData.append("action", 'extech_edit_single_prod');
                formData.append("_ajax_nonce", '<?php echo EDIT_PROD_NONCE ?>');

                $.ajax({
                    type: 'POST',
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    data: formData,
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function(response) {
                        alert(response.data.message);
                        location.reload();
                    }
                });

            });

        });
    </script>
<?php });
?>