<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( !is_user_logged_in(  ) ) {
    return;
}


if (is_active_sidebar( 'top-bar' )) { ?>
    
    <div id="wrapper-top-bar" class="top-bar bg-primary text-white">

        <div class="container-fluid">

            <div class="slick-top-bar">

                <?php dynamic_sidebar( 'top-bar' ); ?>

            </div>

        </div>

    </div>

<?php } ?>

