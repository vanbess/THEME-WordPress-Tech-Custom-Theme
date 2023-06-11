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
add_action('after_setup_theme', function(){
    add_theme_support('woocommerce');
});

// fix upload size limit nonsense
function filter_site_upload_size_limit($size) {
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

add_action('woocommerce_before_shop_loop_item_title', function(){

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

