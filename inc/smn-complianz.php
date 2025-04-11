<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Show the banner when a html element with class 'cmplz-show-banner' is clicked
 */
function cmplz_show_banner_on_click() {
    ?>
    <script>
        function addEvent(event, selector, callback, context) {
            document.addEventListener(event, e => {
                if ( e.target.closest(selector) ) {
                    e.preventDefault();
                    callback(e);
                }
            });
        }
        addEvent('click', '.cmplz-show-banner', function(){
            document.querySelectorAll('.cmplz-manage-consent').forEach(obj => {
                obj.click();
            });
        });
    </script>
    <?php
}
add_action( 'wp_footer', 'cmplz_show_banner_on_click' );

function add_cmplz_show_banner_menu_item($items, $args) {
    if ($args->theme_location == 'legal') {
        $items .= '<li class="menu-item"><a class="nav-link cmplz-show-banner" href="#">'. __( 'Manage consent', 'complianz-gdpr' ) .'</a></li>';
    }
    return $items;
}
add_filter('wp_nav_menu_items', 'add_cmplz_show_banner_menu_item', 10, 2);