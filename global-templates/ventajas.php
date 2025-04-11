<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( is_active_sidebar( 'advantages' ) ) : ?>
	
	<div class="ventajas-wrapper alignfull bg-dark text-white">
		<div class="ventajas-carousel text-center">
			<?php
			if ( isset( $args['textos'] ) && $args['textos'] ) {
				$textos = $args['textos'];
				$textos = explode( '/', $textos );

				if ( isset( $args['iconos'] ) && $args['iconos'] ) {
					$iconos = explode( '/', $iconos );
				} else {
					$icono = '';
					// $icono = '<img width="16" height="16" src="'. get_stylesheet_directory_uri() .'/img/icono-shop-bag.svg" alt="'. __( 'Icono bolsa de la compra', 'smn' ) .'" />';
					$iconos = array_fill(0, count($textos), $icono);
				}
				
				foreach ( $textos as $i => $texto ) { 
					
					$texto = $iconos[$i] . ' ' . $texto;
					?>

					<div class="ventaja">
						<?php echo wpautop( $texto ); ?>
				</div>

				<?php }

			} else {
				dynamic_sidebar( 'advantages' );
			}
			?>
		</div>
	</div>

	<script type="text/javascript">
		jQuery(document).ready(function($) {
			$('.ventajas-carousel').slick({
				slidesToShow: 4,
				slidesToScroll: 1,
				autoplay: true,
				autoplaySpeed: 2000,
				dots: false,
				arrows: false,
				infinite: true,
				responsive: [
					{
						breakpoint: 992,
						settings: {
							slidesToShow: 3
						}
					},
					{
						breakpoint: 480,
						settings: {
							slidesToShow: 1
						}
					}
				]
			});
		});
	</script>
<?php endif; ?>