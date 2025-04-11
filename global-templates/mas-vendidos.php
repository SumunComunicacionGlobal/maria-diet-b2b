<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$terms = get_terms( array( 
	'taxonomy' 		=> 'product_cat', 
	'parent' 		=> 0, 
	'hide_empty' 	=> true,
) );

if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {

	foreach ( $terms as $term ) {

		// $products = do_shortcode( '[products best_selling="true" limit="6" orderby="popularity" order="DESC" category="' . $term->slug . '"]' );
		$products = do_shortcode( '[products visibility="featured" limit="12" orderby="popularity" order="DESC" category="' . $term->slug . '"]' );

		if ( $products && strlen( $products ) > 100 ) { ?>
			
			<section id="wrapper-products-carousel-<?php echo $term->slug; ?>" class="wrapper wrapper-products-carousel">
				<div class="section-header d-flex w-100 gap-2 flex-wrap align-items-end justify-content-space-between">
					<div class="section-header__title flex-grow-1">
						<p class="small mb-0"><?php echo __( 'Los más vendidos', 'smn' ); ?></p>
						<h2 class="mb-2"><?php echo esc_html( $term->name ); ?></h2>
					</div>
					<div class="section-header__action">
						<div class="wp-block-buttons mb-2">
							<div class="wp-block-button is-style-arrow-link">
								<a href="<?php echo get_term_link( $term ); ?>" class="wp-block-button__link py-0"><?php echo sprintf( __( 'Ver todo en %s', 'smn' ), esc_html( $term->name ) ); ?></a>
							</div>
						</div>
					</div>
				</div>
				<div class="products-shortcode-carousel slick-padded">
					<?php echo $products; ?>
				</div>
			</section>

		<?php }

	}

}