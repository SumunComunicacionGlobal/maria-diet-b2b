<?php
/**
 * The sidebar containing the main widget area
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! is_active_sidebar( 'left-sidebar' ) ) {
	return;
}

// when both sidebars turned on reduce col size to 3 from 4.
$sidebar_pos = get_theme_mod( 'understrap_sidebar_position' );
?>

<div class="col-12">
	<button class="facetwp-flyout-open btn btn-outline-dark w-100 mb-2 d-lg-none"><?php echo __( 'Filtrar', 'smn' ); ?> <img class="btn-icon" src="<?php echo get_stylesheet_directory_uri(); ?>/img/icono-filtro.svg" /></button>
</div>

<?php if ( 'both' === $sidebar_pos ) : ?>
	<div class="col-md-3 widget-area d-none d-lg-block" id="left-sidebar">
<?php else : ?>
	<div class="col-md-4 col-lg-3 widget-area d-none d-lg-block" id="left-sidebar">
<?php endif; ?>

<?php dynamic_sidebar( 'left-sidebar' ); ?>

</div><!-- #left-sidebar -->
