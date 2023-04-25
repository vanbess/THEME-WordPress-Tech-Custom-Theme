<p class="p-2 fw-semibold">
    If you would like to bulk import products you can use the input below to upload a CSV file containing the following fields:
</p>

<ul>
    <li>Product title (product_title)</li>
    <li>Product SKU (product_sku)</li>
    <li>Regular price (regular_price)</li>
    <li>Sale price (sale_price) - optional field</li>
    <li>Description (description)</li>
    <li>Stock on hand (stock_on_hand)</li>
    <li>Status (status)</li>
</ul>



<p class="p-2">
    You can download a sample CSV file <a href="">here</a>. Please ensure that the column names of your import file matches the column names of the sample file exactly.
</p>
<p class="p-2 fw-semibold">
    <u><i>NOTE 1:</i></u> The order of your columns are not important, however the column names have to match, else your import will either fail or be only partially successful.
</p>
<p class="p-2 fw-semibold">
    <u><i>NOTE 2:</i></u> The import process <u>does not</u> currently support auto uploading of product images. Once the import has been completed you will need to attach and image to each product manually via the All Products tab.
</p>

<hr class="mb-4">

<div class="mb-4">
    <button class="btn btn-secondary shadow-sm">Download Sample CSV</button>
</div>

<div class="mb-4">
    <label for="import-csv" class="form-label fw-semibold">Upload CSV:</label>
    <input type="file" class="form-control shadow-sm" id="import-csv" name="import-csv">
</div>

<div>
    <button id="process_prod_import" class="btn btn-primary btn-lg w-100 shadow-sm">Process Import</button>
</div>

<!-- process import via AJAX -->
<script>
    jQuery(document).ready(function($) {

    });
</script>