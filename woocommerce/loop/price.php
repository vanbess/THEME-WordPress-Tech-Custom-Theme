<?php

/**
 * Loop Price
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/price.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     1.6.4
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

global $product;

// make sure product price is set before display due to method we used to add products via dashboard
if (!$product->get_price()) :
	if ($product->get_sale_price()) :
		$product->set_price($product->get_sale_price());
		
	else :
		$product->set_price($product->get_regular_price());
	endif;
endif;
?>

<?php if ($price_html = $product->get_price_html()) : ?>
	<span class="shop-loop-price d-block"><?php echo $price_html; ?></span>
<?php endif; ?>