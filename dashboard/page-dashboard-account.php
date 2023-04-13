<?php

/**
 * Template Name: Dashboard Account Page
 */

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

endif;

// PROCESS FORM SUBMISSION
if (isset($_POST['update-shop-info']) && !$err_msg) :

    echo '<pre>';
    print_r($_POST);
    echo '</pre>';

    update_post_meta($shop_id, 'shop_owner_tel',  sanitize_text_field($_POST['shop_owner_tel']));
    update_post_meta($shop_id, 'shop_franchise',  sanitize_text_field($_POST['shop_franchise']));
    update_post_meta($shop_id, 'shop_street_number',  sanitize_text_field($_POST['shop_street_number']));
    update_post_meta($shop_id, 'shop_suburb',  sanitize_text_field($_POST['shop_suburb']));
    update_post_meta($shop_id, 'shop_city',  sanitize_text_field($_POST['shop_city']));
    update_post_meta($shop_id, 'shop_province',  sanitize_text_field($_POST['shop_province']));
    update_post_meta($shop_id, 'shop_postal',  sanitize_text_field($_POST['shop_postal']));


endif;

// restore current blog
restore_current_blog();

// echo '<pre>';
// print_r($shop_meta);
// echo '</pre>';

?>

<!-- Main content -->
<div id="dashboard-cont" class="container py-5 bg-light mb-n5" style="min-height: 90vh;">
    <div class="row py-5 account">

        <!-- SHOP INFO -->
        <div class="offset-3 col-6 text-center">

            <form id="acc-update-shop" action="">

                <h2 class="mb-5">Shop info</h2>

                <?php if ($err_msg) : ?>
                    <p class="text-body bg-danger-subtle fw-normal rounded-3 p-3 mb-5">
                        There is no information related to your shop present in our database. This is a technical error. Please contact us to resolve this issue for you.
                    </p>
                <?php else : ?>
                    <p class="text-body bg-warning-subtle fw-normal rounded-3 p-3 mb-5">
                        Use the inputs below to update your basic shop info as needed. Greyed out inputs cannot be updated.
                    </p>
                <?php endif; ?>

                <!-- owner first name -->
                <input type="text" name="shop_owner_first_last" id="shop_owner_first_last" class="form-control bg-body-secondary mb-3" placeholder="owner first and last name" readonly value="<?php echo isset($shop_meta['shop_owner_first_last'][0]) ? $shop_meta['shop_owner_first_last'][0] : ''; ?>">

                <!-- email -->
                <input type="email" name="shop_owner_email" id="shop_owner_email" class="form-control bg-body-secondary mb-3" placeholder="owner email address" readonly value="<?php echo isset($shop_meta['shop_owner_email'][0]) ? $shop_meta['shop_owner_email'][0] : ''; ?>">

                <!-- tel -->
                <input type="tel" name="shop_owner_tel" id="shop_owner_tel" class="form-control mb-3" placeholder="owner contact number*" required value="<?php echo isset($shop_meta['shop_owner_tel'][0]) ? $shop_meta['shop_owner_tel'][0] : ''; ?>">

                <!-- franchise name -->
                <input type="text" name="shop_franchise" id="shop_franchise" class="form-control mb-3" placeholder="shop franchise, e.g. Engen, Caltex etc*" required value="<?php echo isset($shop_meta['shop_franchise'][0]) ? $shop_meta['shop_franchise'][0] : ''; ?>">

                <!-- shop name -->
                <input type="text" name="shop_name" id="shop_name" class="form-control bg-body-secondary mb-3" placeholder="shop name" readonly value="<?php echo isset($shop_meta['shop_name'][0]) ? $shop_meta['shop_name'][0] : ''; ?>">

                <!-- street name and number -->
                <input type="text" name="shop_street_number" id="shop_street_number" class="form-control mb-3" placeholder="street name and number*" required value="<?php echo isset($shop_meta['shop_street_number'][0]) ? $shop_meta['shop_street_number'][0] : ''; ?>">

                <!-- suburb -->
                <input type="text" name="shop_suburb" id="shop_suburb" class="form-control mb-3" placeholder="suburb*" required value="<?php echo isset($shop_meta['shop_suburb'][0]) ? $shop_meta['shop_suburb'][0] : ''; ?>">

                <!-- city or town -->
                <input type="text" name="shop_city" id="shop_city" class="form-control mb-3" placeholder="city or town*" required value="<?php echo isset($shop_meta['shop_city'][0]) ? $shop_meta['shop_city'][0] : ''; ?>">

                <!-- province -->
                <select class="form-control mb-3" id="shop_province" name="shop_province" required>

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

                    <?php foreach ($opts as $opt) : ?>

                        <option value="">select province</option>

                        <?php if (isset($shop_meta['shop_province'][0]) && $opt === $shop_meta['shop_province'][0]) : ?>
                            <option value="<?php echo $opt; ?>" selected><?php echo $opt; ?></option>
                        <?php else : ?>
                            <option value="<?php echo $opt; ?>"><?php echo $opt; ?></option>
                        <?php endif; ?>

                    <?php endforeach; ?>

                </select>

                <!-- postal code -->
                <input type="text" name="shop_postal" id="shop_postal" class="form-control mb-3" placeholder="postal code*" required value="<?php echo isset($shop_meta['shop_postal'][0]) ? $shop_meta['shop_postal'][0] : ''; ?>">


                <?php if ($err_msg) : ?>
                    <!-- submit -->
                    <button type="submit" class="btn btn-primary btn-md w-100" data-bs-toggle="tooltip" data-bs-placement="top" title="You're not allowed to perform this action until the error mentioned above has been resolved" name="update-shop-info" id="update-shop-info" value="Update shop info">some text here</button>

                    <div class="tooltip" role="tooltip">
                        <div class="tooltip-arrow"></div>
                        <div class="tooltip-inner">
                            Tooltip text
                        </div>
                    </div>
                <?php else : ?>
                    <!-- submit -->
                    <input type="submit" class="btn btn-primary btn-md w-100" name="update-shop-info" id="update-shop-info" value="Update shop info">
                <?php endif; ?>

            </form>

        </div>
    </div>
</div>

<?php get_footer('dashboard'); ?>