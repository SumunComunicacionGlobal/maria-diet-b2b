<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( have_rows( 'promos_footer', 'option' ) ) : ?>

	<div class="wrapper" id="wrapper-footer-promos">

		<div class="<?php echo esc_attr( $container ); ?>" id="footer-promos" tabindex="-1">

			<div class="row g-2">

				<?php while ( have_rows( 'promos_footer', 'option' ) ) : the_row(); 
				
				$promo_title = get_sub_field( 'promo_title' );
				$promo_text = get_sub_field( 'promo_text' );
				$promo_term_link = get_sub_field( 'promo_term_link' );
				$promo_post_link = get_sub_field( 'promo_post_link' );
				$promo_thumbnail_id = get_sub_field( 'promo_thumbnail_id' );
				$promo_button_text = get_sub_field( 'promo_button_text' );
				$promo_tag_text = get_sub_field( 'promo_tag_text' );
			
				?>

					<div class="col-lg-6">

						<?php smn_banner( $promo_title, $promo_text, $promo_term_link, $promo_post_link, $promo_tag_text, $promo_thumbnail_id, $promo_button_text ); ?>

					</div>

				<?php endwhile; ?>

			</div>

		</div>

	</div><!-- #wrapper-footer-promos -->

<?php endif; ?>