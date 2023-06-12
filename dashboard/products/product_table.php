<!-- product table -->
<table id="product_table" class="table mb-5 table-striped">

    <p class="bg-success-subtle fw-semibold text-center p-3 rounded-3 mb-4 shadow-sm">
        All products currently published for your shop are listed below. You can edit a particular product by clicking on the Edit button next to the product in question.

        <!-- add link to shop which is found under child blog path -> shop link -->
        <a class="text-decoration-none fw-semibold" href="<?php echo get_home_url(); ?>/shop" target="_blank">View your shop.</a>

    </p>

    <thead class="bg-dark-subtle text-center rounded-1 mb-2">
        <tr>
            <th scope="col" class="prod_table_th fw-semibold" title="click to sort by product image">Product Image</th>
            <th scope="col" class="prod_table_th fw-semibold" title="click to sort by product title">Product Title</th>
            <th scope="col" class="prod_table_th fw-semibold" title="click to sort by product SKU">Product SKU</th>
            <th scope="col" class="prod_table_th fw-semibold" title="click to sort by product price">Current Price</th>
            <th scope="col" class="prod_table_th fw-semibold" title="click to sort by stock on hand">Stock on hand</th>
            <th scope="col" class="fw-semibold">Edit</th>
            <th scope="col" class="fw-semibold">Delete</th>
        </tr>
    </thead>
    <tbody id="prod_list_body" class="text-center">
        <!-- if no products -->
        <tr id="prod_list_no_prods" class="align-middle d-none">
            <td colspan="7">
                <p id="prod_list_no_prods_text" class="bg-warning-subtle p-3 rounded-3 fw-semibold shadow-sm mt-3"></p>
            </td>
        </tr>
    </tbody>

</table>

<?php
// get current product count and only display load more button if count > 50
$prod_count = wp_count_posts('product');
$total      = $prod_count->publish + $prod_count->draft;

if ($total > 50) : ?>
    <!-- load more products -->
    <div id="load_more_prods_cont" class="d-flex align-items-center justify-content-center">
        <button id="load_more_prods" type="button" class="btn btn-secondary m-auto shadow-sm">Load More</button>
    </div>
<?php endif; ?>

<?php
add_action('wp_footer', function () {

    // get current product count and only display load more button if count > 50
    $prod_count = wp_count_posts('product');
    $total = $prod_count->publish + $prod_count->draft;

?>

    <script id="fetch_edit_save_prods" data-prod-count="<?php echo $total; ?>">
        jQuery(document).ready(function($) {

            // get current pathname
            var pathname = window.location.pathname;

            // set current page
            var page = 1;

            // get total product count
            var prod_count = $('#fetch_edit_save_prods').data('prod-count');

            // fetch initial batch of products (50)
            data = {
                '_ajax_nonce': '<?php echo FETCH_INIT_PRODS ?>',
                'action': 'extech_fetch_prods',
                'page': page
            }

            $.post('<?php echo admin_url('admin-ajax.php'); ?>', data, function(response) {
                if (response.success) {

                    // grab and append products
                    var products = response.data;

                    $.each(products, function(index, product) {

                        var img = '<img class="prod_img border" src="' + product.img_url + '" alt="' + product.title + '">';
                        var title = '<p class="fw-normal m-0">' + product.title + '</p>';
                        var sku = '<p class="fw-normal m-0">' + product.sku + '</p>';
                        var price = '<p class="fw-normal m-0">' + product.price + '</p>';
                        var soh = '<p class="fw-normal m-0">' + product.soh + '</p>';
                        var edit = '<a class="edit_prod btn btn-primary shadow-sm" title="click to edit product" target="_blank" href="' + pathname + '?edit_prod=' + index + '">Edit</a>';
                        var del = '<a class="delete_prod btn btn-danger shadow-sm" title="click to delete product"  data-pid="' + index + '" href="#">Delete</a>';

                        var row = '<tr class="align-middle"><td>' + img + '</td><td>' + title + '</td><td>' + sku + '</td><td>' + price + '</td><td>' + soh + '</td><td>' + edit + '</td><td>' + del + '</td></tr>';

                        $('#prod_list_body').append(row);

                    });

                    // init table sorter once done
                    $('#product_table').tablesorter({
                        headers: {
                            '.sortable': {
                                sorter: 'text'
                            }
                        }
                    });

                } else {
                    $('#prod_list_no_prods_text').text(response.message).removeClass('d-none');
                }
            });

            // load additional products on load more button click
            $('#load_more_prods').click(function(e) {
                e.preventDefault();

                page++;

                data = {
                    '_ajax_nonce': '<?php echo FETCH_INIT_PRODS ?>',
                    'action': 'extech_fetch_prods',
                    'page': page
                }

                $.post('<?php echo admin_url('admin-ajax.php'); ?>', data, function(response) {
                    if (response.success) {

                        // grab and append products
                        var products = response.data;

                        $.each(products, function(index, product) {

                            var img = '<img class="prod_img border" src="' + product.img_url + '" alt="' + product.title + '">';
                            var title = '<p class="fw-semibold m-0">' + product.title + '</p>';
                            var sku = '<p class="fw-semibold m-0">' + product.sku + '</p>';
                            var price = '<p class="fw-semibold m-0">' + product.price + '</p>';
                            var soh = '<p class="fw-semibold m-0">' + product.soh + '</p>';
                            var edit = '<a class="edit_prod btn btn-primary" title="click to edit product" target="_blank" href="' + pathname + '?edit_prod=' + index + '">Edit</a>';
                            var del = '<a class="delete_prod btn btn-danger" title="click to delete product"  data-pid="' + index + '" href="#">Delete</a>';

                            var row = '<tr class="align-middle">><td>' + img + '</td><td>' + title + '</td><td>' + sku + '</td><td>' + price + '</td><td>' + soh + '</td><td>' + edit + '</td><td>' + del + '</td></tr>';

                            $('#prod_list_body').append(row);

                        });

                        // init table sorter once done
                        $('#product_table').tablesorter({
                            headers: {
                                '.sortable': {
                                    sorter: 'text'
                                }
                            }
                        });

                        // hide load more products link/button once all products have been loaded
                        var curr_rows = $('#prod_list_body tr').length;

                        if (curr_rows > prod_count) {
                            $('#load_more_prods_cont').addClass('d-none');
                        }

                    } else {
                        $('#prod_list_no_prods_text').text(response.message).removeClass('d-none');
                    }
                })

            });

            // Delete single product
            $(document).on('click', '.delete_prod', function(e) {

                console.log('clicked');

                e.preventDefault();

                alert('Are you sure you want to delete this product?');

                data = {
                    '_ajax_nonce': '<?php echo DELETE_PROD_NONCE ?>',
                    'action': 'extech_del_single_prod',
                    'pid': $(this).data('pid')
                }

                $.post('<?php echo admin_url('admin-ajax.php'); ?>', data, function(response) {
                    if (!response.success) {
                        alert(response.data.msg);
                        location.reload();
                    } else {
                        alert(response.data.msg);
                        location.reload();
                    }
                })

            });

        });
    </script>


<?php });
?>