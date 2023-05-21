<?php

/**
 * Template Name: Dashboard QR Code Page
 */

get_header('dashboard');

global $post;

// generate shop url
$shop_dets = get_blog_details();
$shop_url  = $shop_dets->siteurl . '/shop/';
$shop_id   = $shop_dets->blog_id;

?>

<!-- Main content -->
<div id="dashboard-cont" class="container mt-5" style="min-height: 90vh;">
    <div class="row qr-code">
        <div class="offset-3 col-6 mt-5">

            <p class="bg-success-subtle p-3 rounded-2 shadow-sm text-center mb-4 fw-semibold">
                The QR code for your store will appear here. If you do not see a QR code, click on the Generate QR Code button to generate one.
            </p>

            <?php if (get_option('qr_code_img')) : ?>
                qr code image here
            <?php else : ?>
                <button class="btn btn-primary btn-lg w-100" onclick="generateQR(event, '<?php echo $shop_url; ?>', '<?php echo $shop_id; ?>')" title="Click to generate the QR code for your shop">Generate QR Code</button>
            <?php endif; ?>

        </div>
    </div>
</div>

<script>
    $ = jQuery;

    // generate QR
    function generateQR(event, shop_url, shop_id) {
        
        data = {
            '_ajax_nonce': '<?php echo wp_create_nonce('generate shop QR') ?>',
            'action': 'extech_generate_qr',
            'shop_id': shop_id,
            'shop_url': shop_url
        }

        $.post('<?php echo admin_url('admin-ajax.php'); ?>', data, function(response) {
            console.log(response)
        })
    }
</script>

<?php get_footer('dashboard'); ?>