<?php

/**
 * Template Name: Dashboard Account Page
 */

//  redirect to login page if user is not logged in
if (!is_user_logged_in()) {
    wp_redirect(site_url('/dashboard/login'));
    exit;
}

get_header('dashboard');

global $post;

// show/hide error message
$err_msg = false;

// get current site url
$curr_site_url = get_bloginfo('url');

// switch to main
switch_to_blog(1);

// setup query
$shops = new WP_Query([
    'post_type'      => 'shop',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'meta_key'       => 'shop_url',
    'meta_value'     => $curr_site_url,
    'fields'         => 'ids'
]);

// check returned posts (if any)
if (empty($shops->posts)) :
    $err_msg = true;
else :

    // get shop id
    $shop_id = $shops->posts[0];

    // get shop meta
    $shop_meta = get_post_meta($shop_id);

endif; ?>

<!-- Main content -->
<div id="dashboard-cont" class="container py-5 mb-5" style="min-height: 90vh;">
    <div class="row py-5 mb-5 account">

        <!-- SHOP INFO -->
        <div class="offset-3 col-6 text-start">

            <form id="acc-update-shop" action="" method="post">

                <h2 class="mb-3 text-center">Shop info</h2>

                <?php if ($err_msg) : ?>
                    <p class="text-body bg-danger-subtle fw-normal text-center rounded-2 p-3 mb-3 shadow-sm">
                        There is no information related to your shop present in our database. This is a technical error. Please contact us to resolve this issue for you.
                    </p>
                <?php else : ?>
                    <p class="text-body bg-success-subtle fw-normal text-center rounded-2 p-3 mb-3 shadow-sm">
                        Use the inputs below to update your basic shop info as needed. Greyed out inputs cannot be updated.
                    </p>
                <?php endif; ?>

                <!-- owner first name -->
                <label for="shop_owner_first_last" class="mb-1 ps-2 fw-semibold"><em>Owner first & last name</em></label>
                <input type="text" name="shop_owner_first_last" id="shop_owner_first_last" class="form-control bg-body-secondary mb-3 shadow-sm" placeholder="owner first and last name" readonly value="<?php echo isset($shop_meta['shop_owner_first_last'][0]) ? $shop_meta['shop_owner_first_last'][0] : ''; ?>">

                <!-- email -->
                <label for="shop_owner_email" class="mb-1 ps-2 fw-semibold"><em>Owner email address</em></label>
                <input type="email" name="shop_owner_email" id="shop_owner_email" class="form-control bg-body-secondary mb-3 shadow-sm" placeholder="owner email address" readonly value="<?php echo isset($shop_meta['shop_owner_email'][0]) ? $shop_meta['shop_owner_email'][0] : ''; ?>">

                <!-- tel -->
                <label for="shop_owner_tel" class="mb-1 ps-2 fw-semibold"><em>Owner telephone number*</em></label>
                <input type="tel" name="shop_owner_tel" id="shop_owner_tel" class="form-control mb-3 shadow-sm" placeholder="owner contact number*" required value="<?php echo isset($shop_meta['shop_owner_tel'][0]) ? $shop_meta['shop_owner_tel'][0] : ''; ?>">

                <!-- franchise name -->
                <label for="shop_franchise" class="mb-1 ps-2 fw-semibold"><em>Franchise/Brand*</em></label>
                <input type="text" name="shop_franchise" id="shop_franchise" class="form-control mb-3 shadow-sm" placeholder="shop franchise, e.g. Engen, Caltex etc*" required value="<?php echo isset($shop_meta['shop_franchise'][0]) ? $shop_meta['shop_franchise'][0] : ''; ?>">

                <!-- shop name -->
                <label for="shop_name" class="mb-1 ps-2 fw-semibold"><em>Shop name</em></label>
                <input type="text" name="shop_name" id="shop_name" class="form-control bg-body-secondary mb-3 shadow-sm" placeholder="shop name" readonly value="<?php echo isset($shop_meta['shop_name'][0]) ? $shop_meta['shop_name'][0] : ''; ?>">

                <!-- street name and number -->
                <label for="shop_street_number" class="mb-1 ps-2 fw-semibold"><em>Street number & name*</em></label>
                <input type="text" name="shop_street_number" id="shop_street_number" class="form-control mb-3 shadow-sm" placeholder="street name and number*" required value="<?php echo isset($shop_meta['shop_street_number'][0]) ? $shop_meta['shop_street_number'][0] : ''; ?>">

                <!-- suburb -->
                <label for="shop_suburb" class="mb-1 ps-2 fw-semibold"><em>Suburb*</em></label>
                <input type="text" name="shop_suburb" id="shop_suburb" class="form-control mb-3 shadow-sm" placeholder="suburb*" required value="<?php echo isset($shop_meta['shop_suburb'][0]) ? $shop_meta['shop_suburb'][0] : ''; ?>">

                <!-- city or town -->
                <label for="shop_city" class="mb-1 ps-2 fw-semibold"><em>City or town*</em></label>
                <input type="text" name="shop_city" id="shop_city" class="form-control mb-3 shadow-sm" placeholder="city or town*" required value="<?php echo isset($shop_meta['shop_city'][0]) ? $shop_meta['shop_city'][0] : ''; ?>">

                <!-- province -->
                <label for="shop_province" class="mb-1 ps-2 fw-semibold"><em>Province*</em></label>
                <select class="form-control mb-3 shadow-sm" id="shop_province" name="shop_province" required>

                    <?php
                    $opts = [
                        "Eastern Cape",
                        "Free State",
                        "Gauteng",
                        "KwaZulu-Natal",
                        "Limpopo",
                        "Mpumalanga",
                        "North West",
                        "Northern Cape",
                        "Western Cape"
                    ]
                    ?>

                    <option value="">select province</option>

                    <?php foreach ($opts as $opt) : ?>
                        <?php if (isset($shop_meta['shop_province'][0]) && $opt === $shop_meta['shop_province'][0]) : ?>
                            <option value="<?php echo $opt; ?>" selected><?php echo $opt; ?></option>
                        <?php else : ?>
                            <option value="<?php echo $opt; ?>"><?php echo $opt; ?></option>
                        <?php endif; ?>

                    <?php endforeach; ?>

                </select>

                <!-- postal code -->
                <label for="shop_postal" class="mb-1 ps-2 fw-semibold"><em>Postal code*</em></label>
                <input type="text" name="shop_postal" id="shop_postal" class="form-control mb-4 shadow-sm" placeholder="postal code*" required value="<?php echo isset($shop_meta['shop_postal'][0]) ? $shop_meta['shop_postal'][0] : ''; ?>">

                <!-- submit -->
                <input type="submit" class="btn btn-primary btn-md w-100 shadow-sm" name="update-shop-info" id="update-shop-info" value="Update shop info">

            </form>

        </div>
    </div>
</div>

<!-- submit form via WP AJAX -->
<script>
    jQuery(document).ready(function($) {

        // submit form via ajax (keep it simple please and use alerts for now)
        $('#acc-update-shop').submit(function(e) {

            // prevent default form submission
            e.preventDefault();

            // if any fields are empty, alert user and stop
            if ($('#shop_owner_tel').val() === '' || $('#shop_franchise').val() === '' || $('#shop_street_number').val() === '' || $('#shop_suburb').val() === '' || $('#shop_city').val() === '' || $('#shop_province').val() === '' || $('#shop_postal').val() === '') {

                // show error message
                alert('Please fill in all required fields.');

                // stop
                return false;

            }

            // setup data object
            var data = {
                'action': 'update_shop_info',
                'shop_owner_tel': $('#shop_owner_tel').val(),
                'shop_franchise': $('#shop_franchise').val(),
                'shop_street_number': $('#shop_street_number').val(),
                'shop_suburb': $('#shop_suburb').val(),
                'shop_city': $('#shop_city').val(),
                'shop_province': $('#shop_province').val(),
                'shop_postal': $('#shop_postal').val(),
                'shop_id': '<?php echo $shop_id; ?>'
            };

            // init ajax post request
            $.post('<?php echo admin_url('admin-ajax.php'); ?>', data, function(response) {

                // alert response and reload page
                alert(response);
                location.reload();
            });

        });

    });
</script>

<?php get_footer('dashboard');
