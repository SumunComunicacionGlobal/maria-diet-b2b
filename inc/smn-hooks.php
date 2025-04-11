<?php
/**
 * Custom hooks.
 *
 * @package understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

add_filter( 'wpcf7_form_tag', 'smn_wpcf7_form_control_class', 10, 2 );
function smn_wpcf7_form_control_class( $scanned_tag, $replace ) {

   $excluded_types = array(
        'acceptance',
        'checkbox',
        'radio',
   );

   if ( in_array( $scanned_tag['type'], $excluded_types ) ) return $scanned_tag;

   switch ($scanned_tag['type']) {
    case 'submit':
        $scanned_tag['options'][] = 'class:btn';
        $scanned_tag['options'][] = 'class:btn-primary';
        break;
    
    default:
        $scanned_tag['options'][] = 'class:form-control';
        break;
   }
   
   return $scanned_tag;
}

add_action( 'loop_start', 'archive_loop_start', 10 );
function archive_loop_start( $query ) {

    if ( is_woocommerce() || isset( $query->query['ignore_row'] ) && $query->query['ignore_row'] ) return false;
    
    if ( ( isset( $query->query['add_row'] ) && $query->query['add_row'] ) || ( is_archive() || is_home() || is_search() ) ) {
        echo '<div class="row">';
    }
}

add_action( 'loop_end', 'archive_loop_end', 10 );
function archive_loop_end( $query ) {

    if ( is_woocommerce() || isset( $query->query['ignore_row'] ) && $query->query['ignore_row'] ) return false;

    if ( ( isset( $query->query['add_row'] ) && $query->query['add_row'] ) || ( is_archive() || is_home() || is_search() ) ) {
        echo '</div>';
    }
}

add_filter( 'body_class', 'smn_body_classes' );
function smn_body_classes( $classes ) {

    if ( is_page() ) {

        $navbar_bg = get_post_meta( get_the_ID(), 'navbar_bg', true );
        if ( 'transparent' == $navbar_bg ) {
            $classes[] = 'navbar-transparent';
        }

        $cmplz_pages = array(
            get_option('cmplz_privacy-statement_custom_page'),
            get_option('cmplz_impressum_custom_page'),
            get_option('cmplz_disclaimer_custom_page')
        );

        if (in_array(get_the_ID(), $cmplz_pages)) {
            $classes[] = 'cmplz-document';
        }

    } else {

    }

    return $classes;
}


add_filter( 'post_class', 'bootstrap_post_class', 10, 3 );
function bootstrap_post_class( $classes, $class, $post_id ) {

    if ( is_woocommerce() ) return $classes;

    if ( is_archive() || is_home() || is_search() || in_array( 'hfeed-post', $class ) ) {
        $classes[] = 'col-sm-6 col-lg-4 mb-5 stretch-linked-block'; 
    }

    return $classes;
}

add_filter( 'understrap_site_info_content', 'site_info_do_shortcode' );
function site_info_do_shortcode( $site_info ) {
    return do_shortcode( $site_info );
}

add_action( 'wp_body_open', 'top_anchor');
function top_anchor() {
    echo '<div id="top"></div>';
}

add_action( 'wp_footer', 'back_to_top' );
function back_to_top() {
    echo '<a href="#top" class="btn btn-light back-to-top"></a>';
}

function es_blog() {

    if( is_singular('post') || is_category() || is_tag() || ( is_home() && !is_front_page() ) ) {
        return true;
    }

    return false;
}

add_filter( 'theme_mod_understrap_sidebar_position', 'cargar_sidebar');
function cargar_sidebar( $valor ) {
    if ( is_singular( 'post' ) ) {
        $valor = 'right';
    } elseif ( is_woocommerce() && is_archive() ) {
        
        if ( is_product_category() ) {
            $display_type = woocommerce_get_loop_display_mode();
            if ( 'subcategories' == $display_type ) {
                $valor = 'none';
            } else {
                $valor = 'left';
            }
        } else {
            $valor = 'left';
        }

    }
    return $valor;
}


function smn_nav_menu_submenu_css_class( $classes, $args, $depth ) {

    if ( !$args->walker && 'primary' === $args->theme_location ) {
        $classes[] = 'dropdown-menu';
        // $classes[] = 'collapse';
    }

    return $classes;

}
add_filter( 'nav_menu_submenu_css_class', 'smn_nav_menu_submenu_css_class', 10, 3 );

function smn_add_menu_item_classes( $classes, $item, $args ) {

    // echo '<pre>'; print_r($args); echo '<pre>';
 
    if ( !$args->walker && 'primary' === $args->theme_location ) {
        $classes[] = "nav-item";

        if( in_array( 'current-menu-item', $classes ) ) {
            $classes[] = "active";
        }

        if ( in_array( 'menu-item-has-children', $classes ) ) {
            $classes[] = 'dropdown';
        }
    
    }
 
    return $classes;
}
add_filter( 'nav_menu_css_class' , 'smn_add_menu_item_classes' , 10, 4 );

function smn_add_menu_link_classes( $atts, $item, $args ) {

    if ( !$args->walker && 'primary' == $args->theme_location ) {

    // echo '<pre>'; print_r($atts); echo '<pre>';

    if ( 0 == $item->menu_item_parent ) {
            $atts['class'] = 'nav-link';
        } else {
            $atts['class'] = 'dropdown-item';
        }
    }

    if ( in_array( 'menu-item-has-children', $item->classes ) ) {
        if ( isset( $atts['class'] ) ) $atts['class'] .= ' dropdown-toggle';
    }

    return $atts;
}
add_filter( 'nav_menu_link_attributes', 'smn_add_menu_link_classes', 10, 3 );

add_filter('nav_menu_item_args', function ($args, $item, $depth) {

    if ( !$args->walker && 'primary' == $args->theme_location ) {
        
        $args->link_after  = '<span class="sub-menu-toggler"></span>';

    }
    return $args;
}, 10, 3);

add_filter( 'parse_tax_query', 'smn_do_not_include_children_in_product_cat_archive' );
function smn_do_not_include_children_in_product_cat_archive( $query ) {
    if ( 
        ! is_admin() 
        && $query->is_main_query()
        && $query->is_tax( 'product_cat' )
    ) {
        $query->tax_query->queries[0]['include_children'] = 0;
    }
}

// add_action( 'wp_footer', 'smn_add_shop_offcanvas' );
function smn_add_shop_offcanvas() {

    if ( !is_user_logged_in() ) return;

    $shop_page_id = wc_get_page_id( 'shop' );
    $shop_page_url = get_permalink( $shop_page_id );

    ?>

    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasShop" aria-labelledby="offcanvasShopLabel">
        
        <div class="offcanvas-header align-items-start justify-content-between">
            <p class="h6 offcanvas-title" id="offcanvasShopLabel"><?php echo __( 'Catálogo', 'smn' ); ?></p>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>

        <div class="offcanvas-body">

            <?php
            
            the_widget( 'WC_Widget_Product_Categories', array(
                'title' => '',
                'orderby' => 'order',
                'dropdown' => false,
                'count' => false,
                'hierarchical' => true,
                'show_children_only' => false,
                'hide_empty' => true,
                'depth' => 1,
            ) );

            ?>

    </div>



    <?php

}

add_action( 'wp_head', 'smn_dynamic_styles' );
function smn_dynamic_styles() {

    $product_cats = get_terms( array(
        'taxonomy' => 'product_cat',
        'meta_query' => array(
            array(
                'key' => 'icon_id',
                'compare' => 'EXISTS',
            ),
        ),
    ) );
    ?>

    <style type="text/css">
        <?php foreach( $product_cats as $cat ) : 
            $img_id = get_field( 'icon_id', $cat );
            $img_url = wp_get_attachment_image_url( $img_id, 'medium' );
            ?>

            .cat-item-<?php echo $cat->term_id; ?> > a {
                background-image: url(<?php echo $img_url; ?>);
            }

        <?php endforeach; ?>
    </style>

    <?php
}

// Modificar enlace #productos si no estás logueado
add_action( 'wp_footer', function() {
    if ( is_user_logged_in() ) return;

    $my_account_link = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
    ?>

    <script type="text/javascript">
        (function($) {
            $(document).ready(function() {
                $('a[href$="#productos"]').each(function() {
                    var $this = $(this);
                    $this.attr( 'href', '<?php echo $my_account_link; ?>' );
                });
            });
        })(jQuery);
    </script>
    <?php
});

add_filter( 'wp_nav_menu_objects', function( $items, $args ) {
    if ( 'primary' === $args->theme_location ) {
        foreach ( $items as $item ) {
            if ( 'product_cat' === $item->object ) {
                $term_id = $item->object_id;
                $subcategories = get_terms( array(
                    'taxonomy' => 'product_cat',
                    'parent'   => $term_id,
                    'hide_empty' => false,
                    'orderby' => 'name',
                ) );

                if ( ! empty( $subcategories ) && ! is_wp_error( $subcategories ) ) {

                    // Add "Ver todo" as the first item
                    $view_all_item = clone $item;
                    $view_all_item->ID = 'view-all-' . $term_id;
                    $view_all_item->title = __( 'Ver todo', 'smn' );
                    // $view_all_item->url = get_term_link( $term_id );
                    $view_all_item->menu_item_parent = $item->ID;
                    $view_all_item->classes[] = 'view-all';
                    $items[] = $view_all_item;

                    foreach ( $subcategories as $subcategory ) {
                        $submenu_item = clone $item;
                        $submenu_item->ID = 'subcat-' . $subcategory->term_id;
                        $submenu_item->title = $subcategory->name;
                        $submenu_item->url = get_term_link( $subcategory );
                        $submenu_item->menu_item_parent = $item->ID;
                        $items[] = $submenu_item;
                    }
                }
            }
        }
    }
    return $items;
}, 10, 2 );