<?php

/**
 * This theme is as simple as it gets, meaning it only uses the bare minimum of WordPress theme related functionality and/or
 * best practices. This has been done as this is a private theme and is not intended for publication in a non-private setting.
 * So please, before someone gets all uppety about "muh, best practices" and "muh, this or that WordPress..." let me stop you right
 * there, and remind you what this theme was built for: being basic and lightweight. Danke schön!
 */

//  Define constants
define('EXTECH_PATH', __DIR__);
define('EXTECH_URI', '/wp-content/themes/exceltech');
define('EXTECH_TDOMAIN', 'extech');

// restore current blog
restore_current_blog();

// enqueue our scripts
add_action('wp_enqueue_scripts', function () {

    // bootstrap
    wp_enqueue_style('bootstrap-icons', EXTECH_URI . '/inc/bootstrap-icons/bootstrap-icons.css', [], '', 'all');
    wp_enqueue_style('bootstrap', EXTECH_URI . '/inc/bootstrap/css/bootstrap.min.css', [], 'v5.3.0-alpha1', 'all');
    wp_enqueue_script('bootstrap', EXTECH_URI . '/inc/bootstrap/js/bootstrap.bundle.min.js', ['jquery'], 'v5.3.0-alpha1', true);

    // theme
    wp_enqueue_style('theme', EXTECH_URI . '/style.css', [], '1.0.0', 'all');

    // jquery table sorter
    wp_enqueue_script('jq-tablesorter', EXTECH_URI . '/assets/js/tablesorter.min.js', ['jquery'], 'v2.31.3', true);
});

// Add WooCommerce support
add_action('after_setup_theme', function () {
    add_theme_support('woocommerce');
});

// fix upload size limit nonsense
function filter_site_upload_size_limit($size)
{
    // Set the upload size limit to 10 MB for users lacking the 'manage_options' capability.
    // 10 MB.
    $size = 1024 * 31000;
    return $size;
}
add_filter('upload_size_limit', 'filter_site_upload_size_limit', 20);

// Registration AJAX
include_once EXTECH_PATH . '/registration/registration_ajax.php';

// Account activation AJAX
include_once EXTECH_PATH . '/account_activation/activation_ajax.php';

/**
 * Disable password change emails
 */
add_filter('wp_password_change_notification_email', function ($send, $user) {
    return false;
});

/**
 * Functions init
 */
include_once EXTECH_PATH . '/inc/functions/fnc_init.php';

/**
 * Setup product related nonces
 */
define('SINGLE_PROD_NONCE', wp_create_nonce('single prod nonce'));
define('MULTIPLE_PROD_NONCE', wp_create_nonce('multiple prod nonce'));
define('IMPORT_PROD_NONCE', wp_create_nonce('import prod nonce'));
define('EDIT_PROD_NONCE', wp_create_nonce('edit prod nonce'));
define('MORE_PROD_NONCE', wp_create_nonce('more prod nonce'));
define('DELETE_PROD_NONCE', wp_create_nonce('delete prod nonce'));
define('DELETE_PRODS_NONCE', wp_create_nonce('delete prods nonce'));
define('FETCH_INIT_PRODS', wp_create_nonce('fetch prods nonce'));

/**
 * Product related AJAX actions
 */
include_once EXTECH_PATH . '/dashboard/products/ajax/add_single_prod.php';
include_once EXTECH_PATH . '/dashboard/products/ajax/delete_multiple_prods.php';
include_once EXTECH_PATH . '/dashboard/products/ajax/delete_single_prod.php';
include_once EXTECH_PATH . '/dashboard/products/ajax/edit_single_prod.php';
include_once EXTECH_PATH . '/dashboard/products/ajax/import_prods.php';
include_once EXTECH_PATH . '/dashboard/products/ajax/show_more_prods.php';
include_once EXTECH_PATH . '/dashboard/products/ajax/fetch_prods.php';

/**
 * Customize login page
 */
add_action('login_head', function () { ?>
    <style type="text/css">
        h1 a {
            background-image: url('<?php echo EXTECH_URI . '/inc/assets/img/logos/logo-darkblue.png' ?>') !important;
            background-size: 173px !important;
            height: 120px !important;
            width: 100% !important;
        }

        .login .button-primary {
            background: #06549C !important;
            border-color: #06549C !important;
        }
    </style>
<?php });

/**
 * Change active nav link on page load
 */
add_action('wp_footer', function () { ?>
    <script>
        $ = jQuery;

        $('.nav-link').removeClass('active');

        jQuery(function($) {

            var curr_page = window.location.href;

            $('div#navbarNav').find('.nav-link').each(function(i, e) {
                if ($(this).attr('href') === curr_page) {
                    $(this).addClass('active');
                }
            });
        });
    </script>
<?php });

/**
 * User related functionality
 */
require_once __DIR__ . '/dashboard/users/ajax_add_user.php';
require_once __DIR__ . '/dashboard/users/ajax_delete_user.php';

remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);

add_action('woocommerce_before_shop_loop_item_title', function () {

    echo '<div class="product-img-cont">';
    echo woocommerce_template_loop_product_thumbnail();
    echo '</div>';
});

/**
 * Redirect login logo to home page
 */
add_filter('login_headerurl', function () {
    return home_url();
});

// remove product single link from products in shop loop
remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);

// add additional classes to product shop loop add to cart link
add_filter('woocommerce_loop_add_to_cart_link', function ($link, $product, $args) {

    $link = str_replace('class="', 'class="btn btn-primary w-100 fw-semibold fs-6 text-uppercase shadow-sm ', $link);

    return $link;
}, 10, 3);

// delete all child blog users when child blog is deleted
add_action('delete_blog', function ($blog_id, $drop) {

    // get all users on child blog
    $users = get_users(['blog_id' => $blog_id]);

    // loop through users and delete
    foreach ($users as $user) {
        wp_delete_user($user->ID);
    }
}, 10, 2);

// disable password changed emails for passwords changed on child blogs
add_filter('send_password_change_email', function ($send, $user) {

    // get current blog id
    $current_blog_id = get_current_blog_id();

    // if user is on child blog, disable password changed email
    if ($current_blog_id !== 1) {
        return false;
    }

    return $send;
}, 10, 2);

// disable admin bar for all users
add_filter('show_admin_bar', '__return_false');

// remove product page links from cart page
add_filter('woocommerce_cart_item_permalink', function ($link, $cart_item, $cart_item_key) {
    return '';
}, 10, 3);

// remove all checkout fields from checkout form except first name, last name, email address and phone number
add_filter('woocommerce_checkout_fields', function ($fields) {

    // remove all fields except first name, last name, email address and phone number
    $fields['billing'] = [
        'billing_first_name' => $fields['billing']['billing_first_name'],
        'billing_last_name' => $fields['billing']['billing_last_name'],
        'billing_email' => $fields['billing']['billing_email'],
        'billing_phone' => $fields['billing']['billing_phone'],
    ];

    return $fields;
}, 10, 1);

// add additional classes to billing form labels and inputs
add_filter('woocommerce_form_field_args', function ($args, $key, $value) {

    // add additional classes to label
    $args['label_class'] = ['form-label fw-bold'];

    // add additional classes to input
    $args['input_class'] = ['form-control'];

    return $args;
}, 10, 3);

// disable additional information input field on checkout page
add_filter('woocommerce_enable_order_notes_field', '__return_false');

// hook to woocommerce thank you page to change billing info section title
add_action('woocommerce_thankyou', function ($order_id) { ?>

    <!-- add script to change billing info section title -->
    <script>
        jQuery(function($) {
            // change billing info section title
            $('.woocommerce-order ').find('.woocommerce-customer-details > h2').text('Your Billing Information');
        });
    </script>

<?php });

// remove product links from order thank you page
add_filter('woocommerce_order_item_permalink', function ($link, $item, $order) {
    return '';
}, 10, 3);

// add additional classes to order thank you page text
add_filter('woocommerce_thankyou_order_received_text', function ($text) {

    // get shop url
    $shop_url = get_permalink(wc_get_page_id('shop'));

    // add additional classes to text
    $text = '<p class="text-center fw-bold fs-6 mb-5 text-black-50 p-2 rounded-2 shadow-sm bg-success-subtle">Thank you, your order has been received. Our staff members are currently picking and packing your items and will bring it to your vehicle soon. Thank you for your support and patience! <a href="' . $shop_url . '">Return to shop</a></p>';

    return $text;
}, 10, 1);

/**
 * Get order line items for orders in dashboard
 */
include_once EXTECH_PATH . '/dashboard/orders/ajax_fetch_orders.php';

/**
 * Check for new orders hook - works via cookie and JS
 */
include_once EXTECH_PATH . '/dashboard/orders/check_new_order_hook.php';

/**
 * Mark order as complete
 */
include_once EXTECH_PATH . '/dashboard/orders/ajax_mark_complete.php';

/************************************************
 * AJAX ACTION to update shop info via dashboard
 ************************************************/
add_action('wp_ajax_update_shop_info', 'update_shop_info');
add_action('wp_ajax_nopriv_update_shop_info', 'update_shop_info');

function update_shop_info()
{

    // debug
    // wp_send_json($_POST);

    // get shop id
    $shop_id = $_POST['shop_id'];

    // PROCESS FORM SUBMISSION

    try {

        // update shop meta
        update_post_meta($shop_id, 'shop_owner_tel',  sanitize_text_field($_POST['shop_owner_tel']));
        update_post_meta($shop_id, 'shop_franchise',  sanitize_text_field($_POST['shop_franchise']));
        update_post_meta($shop_id, 'shop_street_number',  sanitize_text_field($_POST['shop_street_number']));
        update_post_meta($shop_id, 'shop_suburb',  sanitize_text_field($_POST['shop_suburb']));
        update_post_meta($shop_id, 'shop_city',  sanitize_text_field($_POST['shop_city']));
        update_post_meta($shop_id, 'shop_province',  sanitize_text_field($_POST['shop_province']));
        update_post_meta($shop_id, 'shop_postal',  sanitize_text_field($_POST['shop_postal']));

        // return success message
        wp_send_json('Shop info updated successfully!');

        // return error message
    } catch (\Throwable $th) {
        wp_send_json('An error occurred! Details: ' . $th->getMessage());
    }

    // end AJAX
    wp_die();
}
