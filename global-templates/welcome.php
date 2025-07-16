<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

?>


<?php if ( is_user_logged_in(  ) ) : ?>

	<?php if (  !isset($_COOKIE['alert_welcome_shown']) ) : ?>

		<div class="alert alert-light alert-dismissible alert-welcome fade show" role="alert">
	
			<div class="row">
				<div class="col-lg-4">
					<p class="has-medium-font-size fw-bold text-center text-lg-start"><?php echo sprintf( __( 'Te damos la bienvenida, %s', 'smn' ), wp_get_current_user()->display_name ); ?></p>
				</div>
				<div class="col-lg-8">
					<?php echo smn_get_logged_in_user_actions(); ?>
				</div>
			</div>
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>

	<?php endif; ?>

<?php endif; ?>

