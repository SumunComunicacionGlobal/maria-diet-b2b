<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

?>

<?php if ( is_user_logged_in(  ) ) : ?>

	<?php if (  !isset($_COOKIE['alert_welcome_shown']) ) : ?>

		<div class="alert alert-light alert-dismissible alert-welcome fade show" role="alert">
			<?php echo do_shortcode( '[smn_login_form]' ); ?>
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>

	<?php endif; ?>

<?php else : ?>

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

