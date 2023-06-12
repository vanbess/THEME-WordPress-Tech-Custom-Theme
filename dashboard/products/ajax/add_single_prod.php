<?php

defined('ABSPATH') ?: exit();

/**
 * Add single prod AJAX
 */

add_action('wp_ajax_nopriv_extech_add_single_prod', 'extech_add_single_prod');
add_action('wp_ajax_extech_add_single_prod', 'extech_add_single_prod');

function extech_add_single_prod() {

    check_ajax_referer('single prod nonce');

    try {
        //code...


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

        // set up woocommerce product data and insert product
        $product_data = array(
            "name"              => $product_title,
            "regular_price"     => $product_regular_price,
            "description"       => $product_description,
            "short_description" => $product_description,
            "sku"               => $product_sku,
            "stock_quantity"    => $product_stock,
            "stock_status"      => "instock",
            "manage_stock"      => true,
            "status"            => $product_status
        );

        // set sale price if applicable
        if ($product_sale_price) :
            $product_data["sale_price"] = $product_sale_price;
        endif;

        // create new woocommerce product
        $new_product = new WC_Product();

        // set product data
        $new_product->set_props($product_data);

        // save product
        $new_product->save();

        // if product inserted, update associated meta
        if ($new_product) :

            // if product image exists, insert and attach
            if ($product_image["size"] > 0) :

                // upload image
                $upload_dir = wp_upload_dir();
                $image_data = file_get_contents($product_image["tmp_name"]);
                $filename   = $product_image["name"];

                // check if directory exists
                if (wp_mkdir_p($upload_dir["path"])) :
                    $file = $upload_dir["path"] . "/" . $filename;
                else :
                    $file = $upload_dir["basedir"] . "/" . $filename;
                endif;

                // write image data to file
                file_put_contents($file, $image_data);

                // create attachment
                $wp_filetype = wp_check_filetype($filename, null);

                // set up attachment data
                $attachment = array(
                    "post_mime_type" => $wp_filetype["type"],
                    "post_title"     => sanitize_file_name($filename),
                    "post_content"   => "",
                    "post_status"    => "inherit"
                );

                // insert attachment
                $attach_id = wp_insert_attachment($attachment, $file, $new_product->get_id());

                // generate attachment data
                require_once(ABSPATH . "wp-admin/includes/image.php");

                $attach_data = wp_generate_attachment_metadata($attach_id, $file);

                // update attachment data
                wp_update_attachment_metadata($attach_id, $attach_data);

                // set product thumbnail
                set_post_thumbnail($new_product->get_id(), $attach_id);

            endif;

            restore_current_blog();
            wp_send_json_success(array("message" => "Product created successfully."));
        else :
            restore_current_blog();
            wp_send_json_error(array("message" => "There was an error creating the product. The page will now reload so that you can try adding it again."));
        endif;
    } catch (\Throwable $th) {
        wp_send_json_error(array("message" => "There was an error creating the product. Caught: " . $th->getMessage()));
    }
}
