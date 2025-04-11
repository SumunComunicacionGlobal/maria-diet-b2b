<?php
/**
 * Hero setup
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;


if ( have_rows( 'promos_header', 'option' ) ) : ?>

	<div class="" id="wrapper-header-promos">

		<div class="container header-promos-container" id="header-promos" tabindex="-1">

			<?php while ( have_rows( 'promos_header', 'option' ) ) : the_row(); 
			
				$promo_title = get_sub_field( 'promo_title' );
				$promo_text = get_sub_field( 'promo_text' );
				$promo_term_link = get_sub_field( 'promo_term_link' );
				$promo_post_link = get_sub_field( 'promo_post_link' );
				$promo_thumbnail_id = get_sub_field( 'promo_thumbnail_id' );
				$promo_button_text = get_sub_field( 'promo_button_text' );
				$promo_tag_text = get_sub_field( 'promo_tag_text' );
			
				?>

				<div class="header-promo">

					<?php smn_banner( $promo_title, $promo_text, $promo_term_link, $promo_post_link, $promo_tag_text, $promo_thumbnail_id, $promo_button_text ); ?>

				</div>

			<?php endwhile; ?>

		</div>

	</div><!-- #wrapper-header-promos -->

<?php endif; ?>