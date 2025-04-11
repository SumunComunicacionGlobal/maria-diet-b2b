<?php
/**
 * Template Name: Landing page
 *
 * Template for displaying a landing page.
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?> <?php understrap_body_attributes(); ?>>

	<div class="site" id="page">

		<div id="landing-page-wrapper">

			<div class="container" id="content" tabindex="-1">

				<main class="site-main" id="main">

					<?php
					while ( have_posts() ) {
						the_post();
						get_template_part( 'loop-templates/content', 'blank' );
					}
					wp_footer();
					?>

				</main><!-- #main -->

			</div><!-- #content -->

		</div><!-- #landing-page-wrapper -->

	</div><!-- #page -->

	<?php get_footer( 'landing' ); ?>

</body>
</html>
