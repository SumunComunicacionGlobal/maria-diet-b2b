<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<?php 
$products = do_shortcode( '[products on_sale="true" orderby="popularity" order="DESC" limit="8"]' ); 

if ( strlen( $products ) > 100 ) { ?>

	<section id="wrapper-sales" class="wrapper alignfull bg-light">

		<div class="container">

			<h2 class="text-center"><?php echo __( 'Productos rebajados', 'smn' ); ?></h2>

			<div class="products-shortcode-carousel">
				<?php echo $products; ?>
			</div>

		</div>

	</section>

<?php }