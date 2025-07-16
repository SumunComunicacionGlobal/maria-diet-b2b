<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$container = get_theme_mod( 'understrap_container_type' );

if ( is_singular() ) {
	$ocultar_prefooter = get_post_meta( get_the_ID(), 'ocultar_prefooter', true );
	if ( $ocultar_prefooter ) return false;
}

if ( !is_front_page(  ) ) {
	get_template_part( 'global-templates/ventajas' );
}

// get_template_part( 'global-templates/footer-promos' );

if ( !is_front_page() && is_active_sidebar( 'newsletter' ) ) : ?>

	<div id="wrapper-newsletter">

		<div class="<?php echo esc_attr( $container ); ?>" id="newsletter-content" tabindex="-1">
			<?php dynamic_sidebar( 'newsletter' ); ?>
		</div>

	</div>

<?php endif;

if ( is_active_sidebar( 'prefooter' ) ) : ?>

	<div class="wrapper" id="wrapper-prefooter">

		<div class="<?php echo esc_attr( $container ); ?>" id="prefooter-content" tabindex="-1">

			<div class="row">
	
				<?php dynamic_sidebar( 'prefooter' ); ?>

			</div>

		</div>

	</div><!-- #wrapper-prefooter -->

	<?php
endif;
