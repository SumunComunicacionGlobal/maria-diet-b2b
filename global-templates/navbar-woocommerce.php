<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

?>

<div class="main-nav-buttons d-flex align-items-center">

    <?php if ( is_user_logged_in() ) echo do_shortcode( '[xoo_wsc_cart]' ); ?>

    <?php if ( is_user_logged_in() && function_exists( 'tinv_get_option' ) ) { ?>

        <?php $wishlist_page = tinv_get_option( 'page', 'wishlist' ); ?>

        <?php if ( $wishlist_page ) { ?>

            <div class="d-none d-sm-block">
                <a class="wishlist-page-link" href="<?php echo get_permalink($wishlist_page); ?>" role="button">
                </a>
            </div>

        <?php } ?>

    <?php } ?>

<?php 
    $my_account_id = get_option('woocommerce_myaccount_page_id');
    $my_account_link = get_permalink( $my_account_id );
    $my_account_title = get_the_title( $my_account_id );
    ?>

    <div class="dropdown">

        <a class="nav-link main-nav-account-button dropdown-toggle" href="#" id="accountDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/user-icon.svg" alt="<?php echo esc_attr( $my_account_title ); ?>" width="20" height="20">
        </a>

        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="accountDropdown">
        <?php if ( is_user_logged_in() ) : 
            $current_user = wp_get_current_user();
        ?>
            <li class="dropdown-item-text small text-muted">
                <?php echo sprintf( __( 'Hola, %s'), '<span class="fw-bold">' . esc_html( $current_user->display_name ) ) . '</small>'; ?>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="<?php echo esc_url( $my_account_link ); ?>"><?php _e( 'Mi cuenta', 'smn' ); ?></a></li>
            <li><a class="dropdown-item" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', '', $my_account_link ) ); ?>"><?php _e( 'Mis pedidos', 'smn' ); ?></a></li>
            <li><a class="dropdown-item" href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>"><?php _e( 'Salir', 'smn' ); ?></a></li>
        <?php else : ?>
            <?php $acceder_text = is_professional_website() ? __( 'Acceder', 'smn' ) : __( 'Accede o regístrate aquí', 'smn' ); ?>
            <li class="dropdown-item-text small text-muted">
            <?php _e( 'No has iniciado sesión', 'smn' ); ?>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="<?php echo $my_account_link; ?>"><?php echo $acceder_text; ?></a></li>
        <?php endif; ?>
        </ul>
    </div>

</div>
