<?php
/**
 * Hero setup
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$post_type = 'slide';

$args = array(
	'post_type'			=> 'post',
	'posts_per_page'	=> 3,
	'orderby'			=> 'date',
	'order'				=> 'DESC',
);

$q = new WP_Query($args);

if ( $q->have_posts() ) : 
	$has_odd_slides = $q->found_posts % 2;
	$odd_class = $has_odd_slides ? 'has-odd-slides' : '';
	?>

	<div id="wrapper-header-promos">

		<div class="row g-2">

			<div class="col-lg-8 mb-2 mb-lg-1">

				<div class="header-promos-container <?php echo $odd_class; ?>" id="header-promos" tabindex="-1">

					<?php while ( $q->have_posts() ) : $q->the_post(); 

						global $post;					
						?>

						<div class="header-promo">

							<?php get_template_part( 'loop-templates/content', 'cover' ); ?>

						</div>

					<?php endwhile; ?>

				</div>

			</div>


			<?php if ( is_active_sidebar( 'header-promos' ) ) : ?>

				<div class="col-lg-4 mb-1">

					<div class="header-promos-sidebar">

						<?php dynamic_sidebar( 'header-promos' ); ?>

					</div>

				</div>
		
			<?php endif; ?>

		</div>

	</div><!-- #wrapper-header-promos -->

<?php endif;

wp_reset_postdata();
