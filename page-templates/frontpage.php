<?php
/**
 * Template Name: Página de inicio
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();
$container = get_theme_mod( 'understrap_container_type' );
?>

<section id="home-featured" class="bg-light py-2">
	<div class="<?php echo $container; ?>">
		<?php get_template_part( 'global-templates/welcome' ); ?>
		<?php get_template_part( 'global-templates/login' ); ?>
		<?php if ( is_user_logged_in() || !is_professional_website() ) :
			get_template_part( 'global-templates/hero-posts' );
			get_template_part( 'global-templates/subcategories' );
		endif; ?>
	</div>
</section>

<?php $wrapper_id = 'no-title-page-wrapper'; ?>

<div id="<?php echo $wrapper_id; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- ok. ?>">

	<div class="<?php echo esc_attr( $container ); ?>" id="content">

		<div class="content-area" id="primary">

			<main class="site-main" id="main" role="main">

				<?php
				while ( have_posts() ) {
					the_post();
					the_content();
				}
				?>

			</main>


			<?php if ( is_user_logged_in() || !is_professional_website() ) : ?>

				<?php get_template_part( 'global-templates/product-categories-tabs' ); ?>

			<?php endif; ?>

			<?php get_template_part( 'global-templates/ventajas' ); ?>

			<?php if ( is_active_sidebar( 'newsletter' ) ) {
				dynamic_sidebar( 'newsletter' );
			} ?>

			<?php if ( is_user_logged_in() || !is_professional_website() ) : ?>

				<?php // get_template_part( 'global-templates/ofertas' ); ?>

				<?php get_template_part( 'global-templates/mas-vendidos' ); ?>

			<?php endif; ?>

		</div><!-- #primary -->

	</div><!-- #content -->

</div><!-- #<?php echo $wrapper_id; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- ok. ?> -->

<?php
get_footer();
