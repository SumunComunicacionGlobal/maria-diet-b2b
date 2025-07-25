<?php
/**
 * Header Navbar (bootstrap5)
 *
 * @package Understrap
 * @since 1.1.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$container = get_theme_mod( 'understrap_container_type' );
$navbar_class = smn_get_navbar_class();
?>

<nav id="main-nav" class="navbar navbar-expand-lg <?php echo $navbar_class; ?>" aria-labelledby="main-nav-label">

	<h2 id="main-nav-label" class="screen-reader-text">
		<?php esc_html_e( 'Main Navigation', 'understrap' ); ?>
	</h2>


	<div class="<?php echo esc_attr( $container ); ?>">

		<!-- Your site branding in the menu -->
		<?php get_template_part( 'global-templates/navbar-branding' ); ?>

		<div class="d-flex flex-wrap">

			<div class="offcanvas offcanvas-bottom bg-dark" tabindex="-1" id="navbarNavOffcanvas">

				<div class="offcanvas-header justify-content-end">
					<button
						class="btn-close btn-close-white text-reset"
						type="button"
						data-bs-dismiss="offcanvas"
						aria-label="<?php esc_attr_e( 'Close menu', 'understrap' ); ?>"
					></button>
				</div><!-- .offcancas-header -->

				<div class="d-flex flex-column flex-lg-row justify-content-end">

					<!-- The WordPress Menu goes here -->
					<?php
					wp_nav_menu(
						array(
							'theme_location'  => 'primary',
							'container_class' => 'offcanvas-body',
							'container_id'    => '',
							'menu_class'      => 'navbar-nav justify-content-end flex-grow-1 pe-3',
							'fallback_cb'     => '',
							'menu_id'         => 'main-menu',
							'depth'           => 2,
							'walker'          => new Understrap_WP_Bootstrap_Navwalker(),
						)
					);
					?>

					<?php if ( is_user_logged_in() ) get_product_search_form(); ?>

				</div>

			</div><!-- .offcanvas -->

			<?php get_template_part( 'global-templates/navbar-woocommerce' ); ?>

			<button
				class="navbar-toggler"
				type="button"
				data-bs-toggle="offcanvas"
				data-bs-target="#navbarNavOffcanvas"
				aria-controls="navbarNavOffcanvas"
				aria-expanded="false"
				aria-label="<?php esc_attr_e( 'Open menu', 'understrap' ); ?>"
			>
				<span class="navbar-toggler-icon"></span>
			</button>

		</div><!-- .d-flex -->

	</div><!-- .container(-fluid) -->

</nav><!-- #main-nav -->
