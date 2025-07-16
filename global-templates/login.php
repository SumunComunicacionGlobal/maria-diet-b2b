<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

?>


<?php if ( is_professional_website() && !is_user_logged_in() ) : ?>

	<div class="alert alert-light fade show" role="alert">
		<div class="row">
			<div class="col-md-6">
				<?php echo do_shortcode( '[smn_login_form]' ); ?>
			</div>
			<div class="col-md-6">
				<?php dynamic_sidebar( 'register-area' ); ?>
			</div>
		</div>
	</div>

<?php endif; ?>

