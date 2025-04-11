<?php
/**
 * Post rendering content according to caller of get_template_part
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<div <?php post_class( 'wp-block-cover' ); ?> id="post-<?php the_ID(); ?>">

	<span aria-hidden="true" class="wp-block-cover__background has-black-background-color has-background-dim-50 has-background-dim"></span>

	<?php the_post_thumbnail( 'medium_large', array( 'class' => 'wp-block-cover__image-background' ) ); ?>

	<div class="wp-block-cover__inner-container">

		<?php
		the_title( '<p class="h3 content-cover-title">', '</p>' );
		?>

		<a class="btn btn-outline-light stretched-link" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Ver más', 'understrap' ); ?></a>

		<footer class="entry-footer">

			<?php understrap_entry_footer(); ?>

		</footer><!-- .entry-footer -->

	</div>

</div><!-- #post-## -->
