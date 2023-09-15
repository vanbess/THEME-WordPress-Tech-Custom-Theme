<?php

/**
 * Template Name: Dashboard QR Code Page
 */

//  if user is not logged in, redirect to login page
if (!is_user_logged_in()) {
    wp_redirect(site_url('/dashboard/login'));
    exit;
}

get_header('dashboard');

global $post;

// generate shop url
$shop_dets = get_blog_details();
$shop_url  = $shop_dets->siteurl . '/shop/';
$shop_id   = $shop_dets->blog_id;

?>

<script id="qrCodeJs" src="<?php echo EXTECH_URI . '/lib/qrcode.js/qrcode.min.js' ?>"></script>

<!-- Main content -->
<div id="dashboard-cont" class="container mt-5" style="min-height: 90vh;">
    <div class="row qr-code mb-5 pb-5">
        <div class="offset-3 col-6 mt-5 mb-5 pb-5">

            <p class="bg-success-subtle p-3 rounded-2 shadow-sm text-center mb-4 fw-semibold">
                The QR code for your store will appear here. Simply right click on it and select "Save image as..." to save it to your phone or computer. You can then print the QR code so that customers can scan it with their phones to be redirected to your shop.
            </p>

            <div id="qrCode" style="cursor: pointer;" class="position-relative">
                <a href="" id="downloadQR" onclick="downloadQR(event)" class="btn btn-primary w-100 rounded-0 position-absolute shadow-sm" title="click to download QR code" download>Click to Download</a>
            </div>

        </div>
    </div>
</div>

<!-- generate QR code -->
<script>
    $ = jQuery;

    // generate
    var qrcode = new QRCode(document.getElementById("qrCode"), {
        text: "<?php echo $shop_url; ?>",
        width: 1024,
        height: 1024,
        useSVG: false
    });

    // download QR coce
    function downloadQR(event) {

        var dlBtn = $(event.target);

        var imgSrc = $('#qrCode > img').attr('src');
        dlBtn.attr('href', imgSrc);


    }
</script>

<style>
    #qrCode>img {
        width: 100%;
    }
</style>

<?php get_footer('dashboard'); ?>