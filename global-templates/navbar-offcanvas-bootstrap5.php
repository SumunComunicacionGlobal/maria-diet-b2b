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

<nav id="main-nav" class="" aria-labelledby="main-nav-label">

	<div class="navbar navbar-header-first navbar-expand-lg <?php echo $navbar_class; ?>">

		<p id="main-nav-label" class="screen-reader-text">
			<?php esc_html_e( 'Main Navigation', 'understrap' ); ?>
		</p>


		<div class="<?php echo esc_attr( $container ); ?> gap-md-3">

			<!-- Your site branding in the menu -->
			<?php get_template_part( 'global-templates/navbar-branding' ); ?>

			<?php if ( is_user_logged_in() ) {
				echo '<div class="d-none d-lg-block flex-grow-1 mw-500">';
					get_product_search_form(); 
				echo '</div>';
			} ?>

			<div class="d-flex flex-wrap">

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

	</div>

	<div class="navbar navbar-header-second navbar-expand-lg <?php echo $navbar_class; ?>">

		<div class="<?php echo esc_attr( $container ); ?>">

			<div class="offcanvas offcanvas-start bg-light" tabindex="-1" id="navbarNavOffcanvas">

				<div class="offcanvas-header align-items-start justify-content-between">

					<?php get_template_part( 'global-templates/navbar-branding' ); ?>

					<button
							class="btn-close text-reset"
							type="button"
							data-bs-dismiss="offcanvas"
							aria-label="<?php esc_attr_e( 'Close menu', 'understrap' ); ?>"
					></button>

				</div><!-- .offcancas-header -->

				<div class="offcanvas-body">

					<?php if ( is_user_logged_in() ) {
						echo '<div class="d-lg-none mb-3">';

							get_product_search_form(); 

						echo '</div>';
					} ?>

					<!-- The WordPress Menu goes here -->
					<?php
					wp_nav_menu(
						array(
							'theme_location'  => 'primary',
							'container_class' => 'w-100',
							'container_id'    => '',
							'menu_class'      => 'navbar-nav flex-grow-1',
							'fallback_cb'     => '',
							'menu_id'         => 'main-menu',
							'depth'           => 2,
							'walker'          => new Understrap_WP_Bootstrap_Navwalker(),
						)
					);
					?>

				</div>

			</div><!-- .offcanvas -->

		</div><!-- .container(-fluid) -->

	</div>

</nav><!-- #main-nav -->
