<form action="process-product.php" method="POST" enctype="multipart/form-data">

    <!-- Product title -->
    <div class="mb-3">
        <label for="product_title" class="form-label">Product Title *</label>
        <input type="text" class="form-control" id="product_title" name="product_title" required>
    </div>

    <!-- Product SKU -->
    <div class="mb-3">
        <label for="product_sku" class="form-label">Product SKU *</label>
        <input type="text" class="form-control" id="product_sku" name="product_sku" required>
    </div>

    <!-- Product Regular Price -->
    <div class="mb-3">
        <label for="product_regular_price" class="form-label">Product Regular Price *</label>
        <input type="number" step="0.01" class="form-control" id="product_regular_price" name="product_regular_price" required>
    </div>

    <!-- Product Sale Price -->
    <div class="mb-3">
        <label for="product_sale_price" class="form-label">Product Sale Price</label>
        <input type="number" step="0.01" class="form-control" id="product_sale_price" name="product_sale_price">
    </div>

    <!-- Product Description -->
    <div class="mb-3">
        <label for="product_description" class="form-label">Product Description *</label>
        <textarea class="form-control" id="product_description" name="product_description" rows="3" maxlength="250" required></textarea>
    </div>

    <!-- Product Image -->
    <div class="mb-3">
        <label for="product_image" class="form-label">Product Image</label>
        <input type="file" class="form-control" id="product_image" name="product_image">
    </div>

    <!-- Publish/Unpublish Product -->
    <div class="mb-3">
        <label for="product_status" class="form-label">Product Status *</label>
        <select class="form-control" id="product_status" name="product_status" required>
            <option value="" disabled selected>Select product status</option>
            <option value="publish">Publish</option>
            <option value="unpublish">Unpublish</option>
        </select>
    </div>

    <!-- Submit Button -->
    <button type="submit" class="btn btn-primary">Submit</button>

</form>