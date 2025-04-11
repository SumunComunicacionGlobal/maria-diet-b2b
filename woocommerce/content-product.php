<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Check if the product is a valid WooCommerce product and ensure its visibility before proceeding.
if ( ! is_a( $product, WC_Product::class ) || ! $product->is_visible() ) {
	return;
}

// if ( $GLOBALS['woocommerce_loop']['is_shortcode'] ) :
if ( 1 == 1 ) : ?>

	<li <?php wc_product_class( '', $product ); ?>>
		<?php
		/**
		 * Hook: woocommerce_before_shop_loop_item.
		 *
		 * @hooked woocommerce_template_loop_product_link_open - 10
		 */
		do_action( 'woocommerce_before_shop_loop_item' );

		/**
		 * Hook: woocommerce_before_shop_loop_item_title.
		 *
		 * @hooked woocommerce_show_product_loop_sale_flash - 10
		 * @hooked woocommerce_template_loop_product_thumbnail - 10
		 */
		do_action( 'woocommerce_before_shop_loop_item_title' );

		/**
		 * Hook: woocommerce_shop_loop_item_title.
		 *
		 * @hooked woocommerce_template_loop_product_title - 10
		 */
		do_action( 'woocommerce_shop_loop_item_title' );

		/**
		 * Hook: woocommerce_after_shop_loop_item_title.
		 *
		 * @hooked woocommerce_template_loop_rating - 5
		 * @hooked woocommerce_template_loop_price - 10
		 */
		do_action( 'woocommerce_after_shop_loop_item_title' );

		/**
		 * Hook: woocommerce_after_shop_loop_item.
		 *
		 * @hooked woocommerce_template_loop_product_link_close - 5
		 * @hooked woocommerce_template_loop_add_to_cart - 10
		 */
		do_action( 'woocommerce_after_shop_loop_item' );
		?>
	</li>

<?php else : ?>

	<li <?php wc_product_class( 'list-group-item', $product ); ?>>

		<a href="<?php echo $product->get_permalink(); ?>" class="d-block">
			<div class="row g-2">
				<div class="col" style="flex: 0 0 66px;">
					<?php echo $product->get_image( array(50,50)); ?>
				</div>
				<div class="col flex-grow-1">
					<div class="row g-2">
						<div class="col">
							<p class="mb-0"><?php echo $product->get_title(); ?></p>
							<?php echo '<div><span class="sku">' . $product->get_sku() . '</span></div>'; ?>
							<?php smn_show_product_tags(); ?>
						</div>
						<div class="col-lg-3 col-xxl-2 text-end">
							<?php woocommerce_template_loop_price(); ?>
						</div>
					</div>
				</div>
			</div>
		</a>

	</li>

<?php endif; ?>