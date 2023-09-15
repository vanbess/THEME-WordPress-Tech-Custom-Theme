<p class="bg-success-subtle fw-semibold text-center p-3 rounded-3 mb-4 shadow-sm">
    Use the inputs below to add a single product to your shop's inventory. All fields marked with an asterisk (*) are.
</p>

<form id="form_sub_single_prod" action="">

    <!-- Product title -->
    <div class="mb-3">
        <label for="product_title" class="form-label fw-semibold">Product Title *</label>
        <small class="ms-2 form-text text-muted font-italic">the title of this product</small>
        <input type="text" class="form-control shadow-sm" id="product_title">
    </div>

    <!-- Product SKU -->
    <div class="mb-3">
        <label for="product_sku" class="form-label fw-semibold">Product SKU *</label>
        <small class="ms-2 form-text text-muted font-italic">the stock keeping unit this product</small>
        <input type="text" class="form-control shadow-sm" id="product_sku">
    </div>

    <!-- Product Regular Price -->
    <div class="mb-3">
        <label for="product_regular_price" class="form-label fw-semibold">Product Regular Price *</label>
        <small class="ms-2 form-text text-muted font-italic">the regular price for this product</small>
        <input type="number" step="0.01" class="form-control shadow-sm" id="product_regular_price">
    </div>

    <!-- Product Sale Price -->
    <div class="mb-3">
        <label for="product_sale_price" class="form-label fw-semibold">Product Sale Price</label>
        <small class="ms-2 form-text text-muted font-italic">the sale price for this product (optional - overrides regular price)</small>
        <input type="number" step="0.01" class="form-control shadow-sm" id="product_sale_price">
    </div>

    <!-- Product Description -->
    <div class="mb-3">
        <label for="product_description" class="form-label fw-semibold">Product Description *</label>
        <small class="ms-2 form-text text-muted font-italic">a short description for this product</small>
        <textarea class="form-control shadow-sm" id="product_description" rows="4" maxlength="250"></textarea>
    </div>

    <!-- Stock on hand -->
    <div class="mb-3">
        <label for="product_stock" class="form-label fw-semibold">Stock on hand *</label>
        <small class="ms-2 form-text text-muted font-italic">current stock on hand for this product</small>
        <input type="number" id="product_stock" class="form-control shadow-sm" step="1" min="1">
    </div>

    <!-- Product Image -->
    <div class="mb-3">
        <label for="product_image" class="form-label fw-semibold">Product Image</label>
        <small class="ms-2 form-text text-muted font-italic">image for this product (optional but recommended)</small>
        <input type="file" class="form-control shadow-sm" id="product_image">
    </div>

    <!-- Publish/Unpublish Product -->
    <div class="mb-4">
        <label for="product_status" class="form-label fw-semibold">Product Status *</label>
        <small class="ms-2 form-text text-muted font-italic">the status of this product (publish = visible in shop, draft = hidden from shop)</small>
        <select class="form-control shadow-sm" id="product_status">
            <option value="" disabled selected>Select product status</option>
            <option value="publish">Publish</option>
            <option value="draft">Draft</option>
        </select>
    </div>

    <!-- Submit Button -->
    <button id="add_single_product" type="submit" class="btn btn-primary btn-lg w-100 shadow-sm">Add Product</button>

</form>

<?php
add_action('wp_footer', function () {

    // retrieve blog_id
    global $blog_id;

?>

    <script id="sub_single_prod">
        jQuery(document).ready(function($) {

            $("#form_sub_single_prod").submit(function(e) {

                $('#add_single_product').text('Working...');

                e.preventDefault();

                // Check if required fields are filled in
                if ($("#product_title").val() === '') {
                    alert("Please enter a product title.");
                    $('#add_single_product').text('Add Product');
                    return;
                }
                if ($("#product_sku").val() === '') {
                    alert("Please enter a product SKU.");
                    $('#add_single_product').text('Add Product');
                    return;
                }
                if ($("#product_regular_price").val() === '') {
                    alert("Please enter a product regular price.");
                    $('#add_single_product').text('Add Product');
                    return;
                }
                if ($("#product_description").val() === '') {
                    alert("Please enter a product description.");
                    $('#add_single_product').text('Add Product');
                    return;
                }
                if ($("#product_status").val() === '') {
                    alert("Please select a product status.");
                    $('#add_single_product').text('Add Product');
                    return;
                }
                if ($("#product_status").val() === '') {
                    alert("Please select a product status.");
                    $('#add_single_product').text('Add Product');
                    return;
                }
                if ($("#product_stock").val() === '') {
                    alert("Please provide stock on hand for this product.");
                    $('#add_single_product').text('Add Product');
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
                formData.append("action", 'extech_add_single_prod');
                formData.append("_ajax_nonce", '<?php echo SINGLE_PROD_NONCE ?>');

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