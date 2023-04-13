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
    wp_enqueue_style('bootstrap-icons', EXTECH_URI . '/inc/bootstrap-icons/bootstrap-icons.css', [], '', 'all');
    wp_enqueue_style('bootstrap', EXTECH_URI . '/inc/bootstrap/css/bootstrap.min.css', [], 'v5.3.0-alpha1', 'all');
    wp_enqueue_style('theme', EXTECH_URI . '/style.css', [], '1.0.0', 'all');
    wp_enqueue_script('bootstrap', EXTECH_URI . '/inc/bootstrap/js/bootstrap.bundle.min.js', ['jquery'], 'v5.3.0-alpha1', true);
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
add_filter('wp_password_change_notification_email', function($send, $user){
    return false;
});

/**
 * Delete multisite users if there child site is deleted via backend`
 */

// check if is subdirectory install (child site)
// function is_subdirectory_install() {
//     return (strpos($_SERVER['REQUEST_URI'], '/wp-content/') !== false);
// }

// hook to wpmu_delete_blog to remove associated users when child site is deleted
// add_action('wpmu_delete_blog', function ($blog_id, $drop) {

//     // bail early if not child site
//     if (!is_subdomain_install() && !is_subdirectory_install()) :
//         return;
//     endif;

//     // delete all child site users
//     $users = get_users(['blog_id' => $blog_id]);

//     foreach ($users as $user) :
//         wpmu_delete_user($user->ID);
//     endforeach;
// }, 10, 2);

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
