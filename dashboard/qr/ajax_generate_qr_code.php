<?php

defined('ABSPATH') ?: exit();

require_once EXTECH_PATH . '/composer/vendor/autoload.php';

use BaconQrCode\Renderer\Image\EpsImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;


/**
 * Ajax action to generate and save/upload QR code
 */
add_action('wp_ajax_nopriv_extech_generate_qr', 'extech_generate_qr');
add_action('wp_ajax_extech_generate_qr', 'extech_generate_qr');

function extech_generate_qr() {

    check_ajax_referer('generate shop QR');

    try {

        // grab subbed vars
        $site_url = $_POST['shop_url'];
        $site_id  = $_POST['shop_id'];

        // switch to correct site contect
        switch_to_blog($site_id);

        // specify output path
        $outputFile = EXTECH_PATH . '/dashboard/qr/generated/qrcode_' . $site_id . '.svg';

        // init QR code renderer
        $renderer = new ImageRenderer(
            new RendererStyle(400),
            new SvgImageBackEnd()
        );

        // init qr code writer
        $writer = new Writer($renderer);

        // write file to path
        $writer->writeFile($site_url, $outputFile);

        // grab svg file so we can create a jpg from it
        $svgContent = file_get_contents($outputFile);

        // create image
        $image = imagecreatefromstring($svgContent);

        // set output path
        $outputFileJpg = EXTECH_PATH . '/dashboard/qr/generated/qrcode_' . $site_id . '.jpg';

        // save image as jpg
        imagejpeg($image, $outputFileJpg, 100);

        // destroy image to free up memory
        imagedestroy($image);

        // specify image url
        $qrCodeLink = EXTECH_URI . '/dashboard/qr/generated/qrcode_' . $site_id . '.svg';

        // switch back to parent site
        switch_to_blog(1);

        // check for file existence and send success and url if found, else error if not
        if (file_exists($outputFile)) :
            update_option('shop_qr_code_url', $qrCodeLink);
            wp_send_json_success($qrCodeLink);
        else :
            wp_send_json_error('QR code image not found.');
        endif;
    } catch (\Throwable $th) {
        wp_send_json_error('ERROR: ' . $th->getMessage());
    }
}
