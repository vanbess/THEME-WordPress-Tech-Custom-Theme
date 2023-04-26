<?php

defined('ABSPATH') ?: exit();

/**
 * Edit single product AJAX
 */
add_action('wp_ajax_nopriv_extech_edit_single_prod', 'extech_edit_single_prod');
add_action('wp_ajax_extech_edit_single_prod', 'extech_edit_single_prod');

function extech_edit_single_prod() {

    check_ajax_referer('edit prod nonce');

    // grab subbed values and sanitize
    $product_title         = sanitize_text_field($_POST["product_title"]);
    $product_sku           = sanitize_text_field($_POST["product_sku"]);
    $product_regular_price = floatval($_POST["product_regular_price"]);
    $product_sale_price    = isset($_POST["product_sale_price"]) ? floatval($_POST["product_sale_price"]) : false;
    $product_description   = sanitize_text_field($_POST["product_description"]);
    $product_image         = $_FILES["product_image"];
    $product_status        = sanitize_text_field($_POST["product_status"]);
    $product_stock         = intval($_POST['product_stock']);

    // grab current blog id
    $blog_id = intval($_POST['current_blog_id']);

    // switch to blog
    switch_to_blog($blog_id);

    // insert new product
    $new_product = array(
        "post_title"   => $product_title,
        "post_content" => $product_description,
        "post_status"  => $product_status,
        "post_type"    => "product"
    );

    $product_id = wp_insert_post($new_product);

    // if product inserted, update associated meta
    if ($product_id) :

        update_post_meta($product_id, "_sku", $product_sku);
        update_post_meta($product_id, "_regular_price", $product_regular_price);
        update_post_meta($product_id, "_manage_stock", 'yes');
        update_post_meta($product_id, "_stock_status", "instock");
        update_post_meta($product_id, "_stock", $product_stock);

        // set sale price if applicable
        if ($product_sale_price) :
            update_post_meta($product_id, "_sale_price", $product_sale_price);
        endif;

        // if product image exists, insert and attach
        if ($product_image["size"] > 0) :

            $upload_dir = wp_upload_dir();
            $image_data = file_get_contents($product_image["tmp_name"]);
            $filename   = $product_image["name"];

            if (wp_mkdir_p($upload_dir["path"])) :
                $file = $upload_dir["path"] . "/" . $filename;
            else :
                $file = $upload_dir["basedir"] . "/" . $filename;
            endif;

            file_put_contents($file, $image_data);

            $wp_filetype = wp_check_filetype($filename, null);

            $attachment = array(
                "post_mime_type" => $wp_filetype["type"],
                "post_title"     => sanitize_file_name($filename),
                "post_content"   => "",
                "post_status"    => "inherit"
            );

            $attach_id = wp_insert_attachment($attachment, $file, $product_id);

            require_once(ABSPATH . "wp-admin/includes/image.php");

            $attach_data = wp_generate_attachment_metadata($attach_id, $file);

            wp_update_attachment_metadata($attach_id, $attach_data);
            set_post_thumbnail($product_id, $attach_id);

        endif;

        wp_send_json_success(array("message" => "Product updated successfully."));
    else :
        wp_send_json_error(array("message" => "There was an error updating the product. The page will now reload so that you can try editing it again."));
    endif;

    restore_current_blog();

    wp_die();
}
