<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$posts_ids = false;

if ( is_singular() ) {
	$posts_ids = get_post_meta( get_the_ID(), 'related_posts', true );
} elseif( is_tax() ) {
	$posts_ids = get_term_meta( get_queried_object_id(), 'related_posts', true );
}

if ( $posts_ids ) {

	$args = array(
		'post_type'			=> 'any',
		'post__in'			=> $posts_ids,
		'orderby'			=> 'post__in',
		'order'				=> 'ASC',
	);

	$q = new WP_Query($args);

	if ( $q->have_posts() ) { ?>

		<div class="slick-carousel slick-padded related-posts">

			<?php while ( $q->have_posts() ) { $q->the_post();

				get_template_part( 'loop-templates/content', 'post' );

			} ?>

		</div>

	<?php }

	wp_reset_postdata();
}