<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( 
	// !is_front_page() && 
	!is_tax( 'product_tag', NOVEDADES_PRODUCT_TAG_ID )
) {
	return false;
}

$args = array(
	'posts_per_page'	=> 6,
	'ignore_row'		=> true,
);

$q = new WP_Query($args);

if ( $q->have_posts() ) { ?>

	<div class="wrapper blog-block" id="wrapper-blog">

		<div class="container">

			<div class="row mb-2">
				<div class="col-md-6">
					<h2 class="section-title"><?php echo __( 'Últimas noticias', 'smn' ); ?></h2>
				</div>
				<div class="col-md-6 text-md-end">
					<a class="btn btn-outline-dark" href="<?php echo get_permalink( get_option( 'page_for_posts' ) ); ?>"><?php echo __( 'Ver todas', 'smn' ); ?></a>
				</div>
			</div>

			<div class="slick-carousel slick-padded">

				<?php while ( $q->have_posts() ) { $q->the_post();

					get_template_part( 'loop-templates/content', 'post' );

				} ?>

			</div>

		</div>

	</div>

<?php }

wp_reset_postdata();
