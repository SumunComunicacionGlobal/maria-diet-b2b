<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( is_search() ) return;

$parent = false;

if ( is_page() || is_shop() ) { 
	$parent = 0;
	$taxonomy = 'product_cat';
}

if ( is_tax() ) {
	$current_term = get_queried_object();
	$parent = $current_term->term_id;
	$taxonomy = $current_term->taxonomy;
}

if ( $parent === false ) return;

$terms = get_terms( array( 
	'taxonomy' 		=> $taxonomy, 
	'parent' 		=> $parent, 
	'hide_empty' 	=> true,
) );

$bg_style = '';

if ( $parent > 0 ) {
	$color = smn_subcategory_parent_color();
	if ( $color ) {
		$bg_style = ' style="background-color: ' . $color . ';"';
	}
}

if ( $terms ) { 
	
	$slick_class = 'slick-subcategories-carousel';
	if ( $parent == 0 ) {
		$slick_class = ' slick-main-categories-carousel';
	}
	?>

	<div class="subcategories-carousel-wrapper my-1">

		<div class="<?php echo $slick_class; ?> slick-padded">

			<?php foreach ( $terms as $key => $term ) { 
				
				if ( $parent == 0 ) {
					
					$bg_style = ' style="';
					$bg_style .= 'opacity: 1;';
					// $color = smn_subcategory_parent_color( $term );
					$color = get_term_meta( $term->term_id, 'color', true );
					$thumbnail_id = false;
					// $thumbnail_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
					
					// if ( $color ) {
					// 	$bg_style .= 'background-color: ' . $color . ';';
					// }

					if ( !$color ) continue;

					if ( $thumbnail_id ) {
						if ( $color ) {
							$bg_style .= 'background: linear-gradient(45deg, ' . $color . 'ff 30%, ' . $color . '00 );';
						} else {
							$bg_style .= 'background: linear-gradient(45deg, rgba(0,0,0,.6) 30%, rgba(0,0,0,0) );';
						}
					} else {

						if ( $color ) {
							$bg_style .= 'background-color: ' . $color . ';';
						} else {
							$bg_style .= 'background-color: var(--bs-primary);';
						}

					}

					$bg_style .= '"';

					// $bg_style = '';
				}
				
				?>

				<div class="subcategory-carousel-item">

					<div class="wp-block-cover subcategory stretch-linked-block bg-primary">

						<span <?php echo $bg_style; ?> aria-hidden="true" class="wp-block-cover__background has-dark-background-color has-background-dim-30 has-background-dim"></span>

						<?php if ( $parent == 0 ) {
							if ( $thumbnail_id ) {
								echo wp_get_attachment_image( $thumbnail_id, 'large', false, array( 'class' => 'wp-block-cover__image-background' ) );
							}
						} ?>

						<div class="wp-block-cover__inner-container position-relative">

							<div class="d-flex gap-1 justify-content-between">
								
								<div>

									<div class="subcategory-header">

										<p><a href="<?php echo get_term_link( $term ); ?>" class="h6 stretched-link text-white"><?php echo $term->name; ?></a></p>

									</div>

								</div>

								<?php smn_subcategory_parent_icon( $term ); ?>

							</div>

							<div class="subcategory-buttons">

								<a href="<?php echo get_term_link( $term ); ?>" class="btn btn-sm btn-light"><?php echo __( 'Ver productos', 'smn' ); ?></a>

							</div>


						</div>

					</div>

				</div>

			<?php } ?>

		</div>

	</div>

<?php } ?>