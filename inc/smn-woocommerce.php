<?php 

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Make product category URLs not hierarchical
add_filter( 'woocommerce_taxonomy_args_product_cat', 'smn_modify_product_cat_taxonomy' );
function smn_modify_product_cat_taxonomy( $args ) {
    $args['rewrite']['hierarchical'] = false;
    return $args;
}

// Disable the magnifying glass zoom effect on product images
add_action( 'wp', 'disable_zoom_magnifying_glass', 99 );
function disable_zoom_magnifying_glass() {
    remove_theme_support( 'wc-product-gallery-zoom' );
}

// Homogeneiza el breakpoint de WC con el de WP
add_filter( 'woocommerce_style_smallscreen_breakpoint', function() {
    return '782px'; 
});

// replace woocommerce breadcrumbs with smn_breadcrumb
// function woocommerce_breadcrumb() {
//     smn_breadcrumb();
// }

// COMPONER EL PRODUCT SUMMARY

// Quitar product meta
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
// Mover el precio justo antes del botón de añadir al carrito
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );

remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);

add_action('woocommerce_single_product_summary', 'woocommerce_breadcrumb', 1 );
add_action('woocommerce_single_product_summary', 'smn_mostrar_contenido_bajo_titulo', 5 );
add_action('woocommerce_single_product_summary', 'smn_short_description_wrap_before', 19);
add_action('woocommerce_single_product_summary', 'smn_short_description_wrap_after', 21);
// add_action('woocommerce_single_product_summary', 'smn_show_logos_after_short_description', 21);
add_action('woocommerce_single_product_summary', 'smn_show_pvp', 22);
add_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 23 );
add_action('woocommerce_single_product_summary', 'smn_show_promocion_anuncio', 24);
add_action('woocommerce_single_product_summary', 'smn_texto_caja_completa', 32);
add_action('woocommerce_single_product_summary', 'smn_show_product_composition', 33 );
add_action('woocommerce_single_product_summary', 'smn_mostrar_momento_y_zona_terms', 33);
add_action('woocommerce_single_product_summary', 'woocommerce_output_product_data_tabs', 41);
add_action('woocommerce_single_product_summary', 'smn_show_product_meta', 42);
// add_action('woocommerce_single_product_summary', 'smn_texto_marca_personalizada', 45);


// add_action( 'woocommerce_after_single_product_summary', 'smn_show_product_composition', 3 );

// Mover la descripción corta del producto debajo del botón de añadir al carrito
// remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
// add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 30 );

// add_action('woocommerce_single_product_summary', 'woocommerce_breadcrumb', 1);

function smn_short_description_wrap_before() {
    echo '<div class="woocommerce-product-details__short-description__wrapper">';
}

function smn_short_description_wrap_after() {
    smn_show_logos_after_short_description();
    echo '</div>';
}

function smn_mostrar_momento_y_zona_terms() {

    smn_show_product_terms_badges( 'momento' );
    smn_show_product_terms_badges( 'parte-cuerpo' );
}


function smn_show_pvp() {
    $pvp = get_field('pvp');
    if ($pvp) {
        $pvp_field_obj = get_field_object('pvp');
        echo '<p class="price pvp mb-0"><span class="">' . $pvp_field_obj['label'] . ':</span> ' . wc_price($pvp) . '</p>';
    }
}

function smn_texto_caja_completa() {

    $r = '';
    
    $unidades_por_caja = get_field('unidades_por_caja');
    if ($unidades_por_caja) {

        $r .= '<p class="small info-text">';

        $r .= sprintf( __( '%s unidades por caja.'), $unidades_por_caja );

            $promocion_caja_completa = has_term( DESCUENTO_5_CAJA_TERM_ID, 'product_tag');
            if ( $promocion_caja_completa ) {
                
                $product_id = get_the_ID();
                $add_to_cart_url = esc_url( add_query_arg( 'add-to-cart', $product_id ) . '&quantity=' . $unidades_por_caja );
                $r .= ' <a class="fw-bold text-decoration-underline" href="' . $add_to_cart_url . '">' . sprintf( __( 'Añadir %s al pedido', 'smn' ), $unidades_por_caja ) . '</a>';

            }

            $r .= '</p>';
    }

    $fecha_de_entrega = get_field('fecha_de_entrega');
    if ($fecha_de_entrega) {
        $r .= '<p class="small info-text info-text-entrega">';
            $r .= $fecha_de_entrega;
        $r .= '</p>';
    }

    if ( $r ) {
        echo '<div class="d-flex flex-wrap">' . $r . '</div>';
    }

}

function smn_texto_marca_personalizada() {
    if ( is_active_sidebar( 'marca-personalizada' ) ) {

        echo '<div class="alert alert-light border-dark small">';

            dynamic_sidebar( 'marca-personalizada' );

            $logo_id = smn_get_current_user_avatar();
            $btn_text = __( 'Subir mi logo', 'smn' );
            ?>

            <div class="row">
                <div class="col-lg-6">
            
                    <?php 
                    if ( $logo_id ) {
                        echo wpautop( __( 'Este es tu logotipo actual:', 'smn' ) );
                        $btn_text = __( 'Cambiar mi logo', 'smn' );
                    } else {
                        echo wpautop( __( 'Aún no has subido tu logotipo personalizado.', 'smn' ) );
                    }
                    ?>

                </div>
                <div class="col-lg-6">
                    <?php echo wp_get_attachment_image( $logo_id, 'medium', false, array( 'class' => 'img-fluid' ) ); ?>
                    <p><a class="btn btn-sm btn-outline-dark" href="<?php echo wc_get_page_permalink( 'myaccount' ); ?>"><?php echo $btn_text; ?></a></p>
                </div>
            </div>


            <?php
        echo '</div>';

    }
}

// remove loop product category count mark
add_filter( 'woocommerce_subcategory_count_html', '__return_null' );

add_filter( 'woocommerce_product_review_comment_form_args', 'smn_review_form_args' );
function smn_review_form_args( $args ) {

    foreach( $args['fields'] as $key => $field_html ) {
        $args['fields'][$key] = str_replace( 
            array(
                '<input ', 
                '<textarea ',
            ),
            array(
                '<input class="form-control" ', 
                '<textarea class="form-control" ', 
            ),
        $field_html );
    }

    return $args;
}

// remove the subcategories from the product loop
remove_filter( 'woocommerce_product_loop_start', 'woocommerce_maybe_show_product_subcategories' );

// add subcategories before the product loop (yet after catalog_ordering and result_count -> see priority 40)
add_action( 'woocommerce_before_shop_loop', 'smn_show_product_subcategories', 40 );
function smn_show_product_subcategories() {
    $subcategories = woocommerce_maybe_show_product_subcategories();
        if ($subcategories) {
          echo '<div class="row subcategories">'.$subcategories.'</div>';
    }
}

add_action( 'pre_get_posts', 'smn_modify_taxonomy_query' );
function smn_modify_taxonomy_query( $query ) {
    if ( ! is_admin() && $query->is_main_query() && is_tax() ) {
        $query->set( 'posts_per_page', 36 );
    }
}

function smn_show_product_meta() {

    echo '<div class="product-meta__group">';

        smn_print_acf_group_fields();
        smn_show_product_taxonomies();

    echo '</div>';
}

function smn_show_product_taxonomies() {
    global $product;
    $taxonomies = get_object_taxonomies('product');
    $exclude_taxonomies = array(
        'product_type',
        'product_visibility',
        'product_shipping_class',
        'product_tag',
        'product_cat',
        'propiedad',
        'marca',
        'product_brand',
        'momento',
        'parte-cuerpo',
        'certificacion',
        'edad',
        'tipo-piel',
        'tipo-producto'
    );

    $taxonomies = array_diff($taxonomies, $exclude_taxonomies);

    foreach ($taxonomies as $taxonomy) {
        $term_list = get_the_term_list($product->get_id(), $taxonomy, '', ', ', '');
        if ($term_list) {
            $term_list = strip_tags($term_list);
            $taxonomy_obj = get_taxonomy($taxonomy);
            echo '<div class="product-meta__item"><span class="product_meta__label">' . esc_html($taxonomy_obj->labels->singular_name) . ':</span> <span class="product_meta__value">' . $term_list . '</span></div>';
        }
    }
}

function smn_show_product_terms_badges( $taxonomy = false ) {
    global $product;

    if ( !$taxonomy ) {
        return;
    }

    $terms = get_the_terms( $product->get_id(), $taxonomy );
    if ( ! $terms || is_wp_error( $terms ) ) {
        return;
    }

    echo '<span class="badges">';
        foreach ( $terms as $term ) {
            echo '<span class="badge bg-light text-dark me-1 mb-1">' . esc_html( $term->name ) . '</span>';
        }
    echo '</span>';
}

function smn_print_acf_group_fields() {
    $group_id = 2236;
    $fields = acf_get_fields($group_id);

    if ($fields) {
        // echo '<ul class="list-group mb-4">';

        
        $envase_text = '';

        $envase_tipo = get_field('envase_tipo');
        $capacidad = get_field('capacidad');
        $envase_material = get_field('envase_material');

        if ( $capacidad ) {
            $capacidad = trim( $capacidad, '.' );
            $capacidad = str_replace( ' ', '&nbsp;', $capacidad );
            $envase_text .= $capacidad;
        }

        if ( $envase_tipo ) {
            $envase_text = $envase_tipo . ' ' . $envase_text;
        }

        if ( $envase_material ) {
            if ( $envase_text ) {
                $envase_text .= '. ';
            }
            $envase_text .= $envase_material;
        }

        if ( $envase_text ) {
            echo '<div class="product-meta__item"><span class="product_meta__label">' . __( 'Envase', 'smn' ) . ':</span> <span class="product_meta__value">' . $envase_text . '</span></div>';
        }

        foreach ($fields as $field) {

            if ( in_array($field['name'], array('envase_tipo', 'envase_material')) ) {
                continue;
            }

            $value = get_field($field['name']);
            
            $append = isset( $field['append'] ) && $field['append'] ? $field['append'] : '';
            $prepend = isset( $field['prepend'] ) && $field['prepend'] ? $field['prepend'] : '';

            if ($value) {
                if ( $field['name'] === 'pvp' ) {
                    $value = wc_price($value);
                }
                echo '<div class="product-meta__item"><span class="product_meta__label">' . esc_html($field['label']) . ':</span> <span class="product_meta__value">' . $prepend . $value . $append . '</span></div>';
            }
        }
        // echo '</ul>';
    }
}

function smn_show_product_composition() {

    $group_id = 2217;
    $fields = acf_get_fields($group_id);

    $r = '';

    if ($fields) {

        foreach ($fields as $field) {
            $value = get_field($field['name']);
            
            $append = isset( $field['append'] ) && $field['append'] ? $field['append'] : '';
            $prepend = isset( $field['prepend'] ) && $field['prepend'] ? $field['prepend'] : '';

            if ($value) {
                $r .= '<div class="composicion-producto__item col-sm-4 mb-1 text-center">';
                    $r .= '<div class="composicion-producto__value h4 mb-0">' . $prepend . $value . $append . '</div>';
                    $r .= '<div class="composicion-producto__label">' . esc_html($field['label']) . '</div>';
                $r .= '</div>';
            }
        }

        if ( $r ) $r = '<div class="composicion-producto row mb-1 justify-content-center">' . $r . '</div>';

    }

    echo $r;

}

// add_filter('term_links-product_cat', 'smn_remove_links_from_terms');
// add_filter('term_links-product_tag', 'smn_remove_links_from_terms');
// add_filter('term_links-post_tag', 'smn_remove_links_from_terms');
// add_filter('term_links-category', 'smn_remove_links_from_terms');

function smn_remove_links_from_terms($links) {
    foreach ($links as &$link) {
        $link = preg_replace('/<a\s+[^>]+>([^<]+)<\/a>/', '<span>$1</span>', $link);
    }
    return $links;
}

// Mostrar campo promocion_anuncio en la página de producto antes del botón de añadir al carrito
function smn_show_promocion_anuncio() {

    global $product;

    $badge_class = 'badge bg-light text-dark';
    $tags = wc_get_product_tag_list( $product->get_id(), '</span> <span class="'. $badge_class .'">', '<span class="'. $badge_class .'">', '</span>' );
    if ( $tags ) {
        echo '<p class="badges">';
            echo strip_tags($tags, '<span>');
        echo '</p>';
    }

    
    // $promocion_anuncio = get_field('promocion_anuncio');
    // if ($promocion_anuncio) {
    //     echo '<p class="promocion-anuncio"><span class="fw-bold">' . __( 'Promoción', 'smn' ) . '</span>: ' . esc_html($promocion_anuncio) . '</p>';
    // }
}

// Desactivar productos relacionados
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

// Desactivar el título de las tabs de woocommerce
add_filter('woocommerce_product_description_heading', '__return_false');

// Habilitar la búsqueda por SKU en el front
add_filter( 'posts_search', 'smn_product_search_by_sku', 9999, 2 );
  
function smn_product_search_by_sku( $search, $wp_query ) {
   global $wpdb;
   if ( is_admin() || ! is_search() || ! isset( $wp_query->query_vars['s'] ) || ( ! is_array( $wp_query->query_vars['post_type'] ) && $wp_query->query_vars['post_type'] !== "product" ) || ( is_array( $wp_query->query_vars['post_type'] ) && ! in_array( "product", $wp_query->query_vars['post_type'] ) ) ) return $search; 
   $product_id = wc_get_product_id_by_sku( $wp_query->query_vars['s'] );
   if ( ! $product_id ) return $search;
   $product = wc_get_product( $product_id );
   if ( $product->is_type( 'variation' ) ) {
      $product_id = $product->get_parent_id();
   }
   $search = str_replace( 'AND (((', "AND (({$wpdb->posts}.ID IN (" . $product_id . ")) OR ((", $search );  
   return $search;   
}


add_shortcode( 'smn_register_form', 'smn_separate_registration_form' );
function smn_separate_registration_form() {

    if ( is_user_logged_in() ) {
        $text = wpautop( __( 'Ya estás registrado y tienes la sesión iniciada', 'smn' ) );
        $text .= smn_get_logged_in_user_actions();
        return $text;
    }

    ob_start();
    do_action( 'woocommerce_before_customer_login_form' );
    $html = wc_get_template_html( 'myaccount/form-login.php' );
    $dom = new DOMDocument();
    $dom->encoding = 'utf-8';
    $dom->loadHTML( utf8_decode( $html ) );
    $xpath = new DOMXPath( $dom );
    $form = $xpath->query( '//form[contains(@class,"register")]' );
    $form = $form->item( 0 );
    echo $dom->saveHTML( $form );
    return ob_get_clean();
}

function smn_get_logged_in_user_actions() {
    return wpautop( sprintf(
        __( 'Ve a tu <a href="%s">área de cliente</a> para consultar <a href="%s">tus últimos pedidos</a> o <a href="%s">navega por el catálogo</a>.', 'smn' ),
        esc_url( wc_get_page_permalink( 'myaccount' ) ),
        esc_url( wc_get_account_endpoint_url( 'orders' ) ),
        esc_url( home_url('#productos') )
    ) );
}
 
add_shortcode( 'smn_login_form', 'smn_separate_login_form' );
function smn_separate_login_form() {

    $title = __( 'Acceder como cliente', 'smn' );
    if ( is_user_logged_in() ) {
        $title = sprintf( __( 'Te damos la bienvenida, %s', 'smn' ), wp_get_current_user()->display_name );
    }

    $text = '<p class="has-medium-font-size fw-bold">' . $title . '</p>';

    if ( is_user_logged_in() ) {
        // $text .= wpautop( __( 'Ya tienes la sesión iniciada', 'smn' ) );
        $text .= smn_get_logged_in_user_actions();
        return $text;
    }

    ob_start();
    do_action( 'woocommerce_before_customer_login_form' );
    echo $text;
    woocommerce_login_form( array( 'redirect' => wc_get_page_permalink( 'myaccount' ) ) );
    return ob_get_clean();
}

// Disable logout confirmation 
add_action( 'template_redirect', 'wc_bypass_logout_confirmation' );
function wc_bypass_logout_confirmation() {
    global $wp;
 
    if ( isset( $wp->query_vars['customer-logout'] ) ) {
        wp_redirect( str_replace( '&amp;', '&', wp_logout_url( wc_get_page_permalink( 'myaccount' ) ) ) );
        exit;
    }
}

// Redirect to homepage after login in woocommerce
add_filter( 'woocommerce_login_redirect', 'smn_login_redirect', 10, 2 );
function smn_login_redirect( $redirect, $user ) {
    return home_url();
}

add_action('smn_before_main_content', 'smn_show_account_notice');
function smn_show_account_notice() {

    if ( 
        ( function_exists( 'smn_get_current_user_avatar') && smn_get_current_user_avatar() ) ||
        current_user_can( 'edit_posts' ) ||
        is_account_page()
    ) {
        return;
    }

    if (is_user_logged_in() && !isset($_COOKIE['account_notice_shown'])) {
        ?>
        <div class="alert alert-info alert-dismissible fade show alert-go-to-my-account" role="alert">

            <?php echo sprintf( 
                __('Puedes revisar %saquí%s los detalles de tu cuenta de cliente. <br>Cambia el logotipo por el tuyo, actualiza tus datos y repite tus últimos pedidos.', 'smn'),
                '<a href="'. esc_url(wc_get_page_permalink('myaccount')) .'">',
                '</a>'
            ); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

        </div>
        <?php
    }
}

add_action('template_redirect', 'smn_redirect_to_my_account_if_not_logged_in');
function smn_redirect_to_my_account_if_not_logged_in() {

    if ( is_page() ) return;

    if ( is_user_logged_in() || is_account_page() ) {
        return;
    }

    // $public_pages_ids = get_field('public_pages', 'option');
    // if ( $public_pages_ids && is_singular() && in_array(get_the_ID(), $public_pages_ids) ) {
    //     return;
    // }

    wp_safe_redirect(wc_get_page_permalink('myaccount'));
    exit;

}

add_filter('wp_nav_menu_objects', 'smn_exclude_menu_items', 10, 2);
function smn_exclude_menu_items($items, $args) {
    
    if (is_user_logged_in()) {
        return $items;
    }

    // $public_pages_ids = get_field('public_pages', 'option');
    foreach ($items as $key => $item) {

        if ( 
            'page' == $item->object
        ) continue;

        if ( 'custom' == $item->object && is_user_logged_in() ) {
            continue;
        }

        // if (!in_array($item->object_id, $public_pages_ids)) {
            unset($items[$key]);
        // }
    }

    return $items;
}


remove_action( 'woocommerce_shop_loop_header', 'woocommerce_product_taxonomy_archive_header', 10 );

function understrap_woocommerce_wrapper_start() {
    
    $container = get_theme_mod( 'understrap_container_type' );
    if ( false === $container ) {
        $container = '';
    }

    $is_product = is_product();

    if ( $is_product ) { ?>

        <div class="wrapper woocommerce-single-product-wrapper" id="woocommerce-wrapper">
    
    <?php } else { 
        
        $wrapper_classes = 'py-2';
        $block_cover_class = 'is-light';

        $thumbnail_id = false;
        $video_id = get_term_meta( get_queried_object_id(), 'video_id', true );
        $background_dim_class = 'has-black-background-color has-background-dim has-background-dim-0';
        
        if ( is_tax( 'product_cat' ) ) {
            $thumbnail_id = smn_subcategory_parent_image();
        } elseif ( is_tax( 'product_tag' ) ) {
            $thumbnail_id = get_term_meta( get_queried_object_id(), 'thumbnail_id', true );
        }

        $video_dim = get_term_meta( get_queried_object_id(), 'video_dim', true );
        if ( !$video_dim ) {
            $video_dim = 0;
        }
        $background_dim_class = 'has-black-background-color has-background-dim has-background-dim-' . $video_dim;

        if ( $video_id ) {
            $wrapper_classes .= ' has-bg-image has-bg-video';
            $block_cover_class = '';
        } elseif ( $thumbnail_id ) {
            $wrapper_classes .= ' has-bg-image';
            $block_cover_class = '';
        }

        if ( is_search() ) {
            $wrapper_classes = 'pb-1';
        }
        ?>

        <div class="wrapper pt-0" id="woocommerce-wrapper">

            <section id="woocommerce-products-header-wrapper" class="wrapper <?php echo $wrapper_classes; ?>">
                
                <div class="<?php echo esc_attr( $container ); ?>">

                    <div class="wp-block-cover <?php echo $block_cover_class; ?>">

                        <span aria-hidden="true" class="wp-block-cover__background <?php echo $background_dim_class; ?>"></span>

                        <?php if ($video_id) {
                            echo '<video class="wp-block-cover__video-background" autoplay muted loop playsinline>';
                                echo '<source src="' . wp_get_attachment_url( $video_id ) . '" type="video/mp4">';
                            echo '</video>';
                        } elseif ( $thumbnail_id ){
                            echo wp_get_attachment_image( $thumbnail_id, 'full', false, array( 'class' => 'wp-block-cover__image-background' ) );
                        } ?>

                        <div class="wp-block-cover__inner-container">

                            <div class="row woocommerce-products-header-title">
                                <div class="col-md-8 col-lg-9">
                                    <?php woocommerce_breadcrumb(); ?>
                                    <?php woocommerce_product_taxonomy_archive_header(); ?>
                                </div>
                                <div class="col-md-4 col-lg-3 d-none d-md-flex justify-content-end">
                                    <?php smn_subcategory_parent_icon(); ?>
                                </div>
                            </div>

                            <?php // get_template_part( 'global-templates/subcategories' ); ?>

                            <?php echo smn_get_back_button(); ?>

                        </div>

                    </div>


                </div>

            </section>

    <?php } ?>

    <div class="<?php echo esc_attr( $container ); ?>" id="content" tabindex="-1">

        <div class="row">
            <?php get_template_part( 'global-templates/left-sidebar-check' ); ?>
            <main class="site-main" id="main">
        
    
<?php }


add_filter('woocommerce_get_price_suffix', 'smn_add_tax_rate_to_price_suffix', 10, 2);
function smn_add_tax_rate_to_price_suffix($suffix, $product) {

    // if ( is_singular() ) {
    //     $suffix = '<span class="badge bg-light text-dark ms-1">' . $suffix . '</span>';
    // } else {
    //     $suffix = '';
    // }

    $search = '{tax_label}';

    if (strpos($suffix, $search) === false) {
        return $suffix;
    }

    if ($product->is_taxable()) {
        $tax_rate = WC_Tax::get_rates($product->get_tax_class());
        if (!empty($tax_rate)) {
            $tax_rate = reset($tax_rate);
            $label = $tax_rate['label'];
            $suffix = str_replace($search, $label, $suffix);
        }
    }

    if ( $suffix ) {
        $suffix = '<span class="badge bg-light text-dark">' . $suffix . '</span>';
    }
    return $suffix;

}

function smn_subcategory_image( $term = null ) {
    if ( ! $term ) {
        $term = get_queried_object();
    }

    $thumbnail_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
    if ( $thumbnail_id ) {
        echo wp_get_attachment_image( $thumbnail_id, 'thumbnail', false, array( 'class' => 'tab-image img-thumbnail' ) );
    } else {
        $placeholder_image = get_option( 'woocommerce_placeholder_image', 0 );
        echo wp_get_attachment_image( $placeholder_image, 'thumbnail', false, array( 'class' => 'tab-image img-thumbnail' ) );
    }

}

function smn_subcategory_icon( $term = null ) {
    if ( ! $term ) {
        $term = get_queried_object();
    }

    $icon_id = get_term_meta( $term->term_id, 'icon_id', true );
    if (!$icon_id) return false;

    $color = get_term_meta( $term->term_id, 'color', true );
    if ( $icon_id ) {
        echo wp_get_attachment_image( $icon_id, 'thumbnail', false, array( 'class' => 'tab-image img-icon' ) );
    } else {
        $thumbnail_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
        if ( $thumbnail_id ) {
            echo wp_get_attachment_image( $thumbnail_id, 'thumbnail', false, array( 'class' => 'tab-image img-thumbnail' ) );
        } else {
            $placeholder_image = get_option( 'woocommerce_placeholder_image', 0 );
            echo wp_get_attachment_image( $placeholder_image, 'thumbnail', false, array( 'class' => 'tab-image img-thumbnail' ) );
        }
    }

    if ( $color ) {
        echo '<p class="category-color-spot" style="background-color: ' . $color . ';"></p>';
    }
}

function smn_subcategory_parent_icon( $term = null ) {

    if ( ! $term && is_tax() ) {
        $term = get_queried_object();
    }

    if ( ! $term ) {
        return;
    }

    if ( 'product_brand' == $term->taxonomy ) {
        $thumbnail_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
        if ( $thumbnail_id ) {
            echo wp_get_attachment_image( $thumbnail_id, 'medium', false, array( 'class' => 'mb-1' ) );
        } else {
            if ($term) {
                echo '<p class="h5">' . esc_html($term->name) . '</p>';
            }
        }
    }

    $r = '';

    $icon_id = get_term_meta( $term->term_id, 'icon_id', true );
    $color = get_term_meta( $term->term_id, 'color', true );
    if ( $icon_id ) {
        $r .= wp_get_attachment_image( $icon_id, 'thumbnail', false, array( 'class' => 'mb-1' ) );
    } else {
        $ancestors = get_ancestors($term->term_id, 'product_cat');
        if (!empty($ancestors)) {
            $ancestor_id = array_pop($ancestors);
            $icon_id = get_term_meta($ancestor_id, 'icon_id', true);
            $color = get_term_meta($ancestor_id, 'color', true);
            if ($icon_id) {
                $r .= wp_get_attachment_image($icon_id, 'thumbnail', false, array( 'class' => 'mb-1' ));
            }
        }
    }

    if ( $color ) {
        $r .= '<p class="category-color-spot" style="background-color: ' . $color . ';"></p>';
    }

    if ( $r ) {
        echo '<div class="subcategory-icon-wrapper d-flex flex-column align-items-center">' . $r . '</div>';
    }

}

function smn_subcategory_parent_color( $term = null ) {

    if ( ! $term ) {
        $term = get_queried_object();
    }

    $color = false;

    $color = get_term_meta( $term->term_id, 'color', true );
    if ( !$color ) {
        $ancestors = get_ancestors($term->term_id, 'product_cat');
        if (!empty($ancestors)) {
            $ancestor_id = array_pop($ancestors);
            $color = get_term_meta($ancestor_id, 'color', true);
        }
    }

    return $color;
}

function smn_subcategory_parent_image( $term = null ) {

    if ( ! $term ) {
        $term = get_queried_object();
    }

    $thumbnail_id = get_term_meta( $term->term_id, 'thumbnail_id', true );

    if ( !$thumbnail_id ) {
        $ancestors = get_ancestors($term->term_id, 'product_cat');
        if (!empty($ancestors)) {
            $ancestor_id = array_pop($ancestors);
            $thumbnail_id = get_term_meta($ancestor_id, 'thumbnail_id', true);
        }
    }

    return $thumbnail_id;
}

function woocommerce_template_loop_product_title() {
    echo '<p class="mt-2 ' . esc_attr( apply_filters( 'woocommerce_product_loop_title_classes', 'woocommerce-loop-product__title' ) ) . '">' . get_the_title() . '</p>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

add_filter( 'the_content', 'smn_add_product_tabs_after_content', 5 );
function smn_add_product_tabs_after_content( $content ) {
    if (is_product()) {

        $product_tabs = apply_filters( 'woocommerce_product_tabs', array() );
        $tabs = '';
        if ( ! empty( $product_tabs ) ) {

            foreach ( $product_tabs as $key => $product_tab ) {
                if ($key === 'description') {
                    continue;
                }

                ob_start();
                call_user_func($product_tab['callback'], $key, $product_tab);
                $tabs .= ob_get_clean();

            }

        }

        return $content . $tabs;

    }

    return $content;
}

add_filter('the_content', 'smn_convert_content_to_accordion');
function smn_convert_content_to_accordion($content) {
    if (!is_product()) {
        return $content;
    }

    if ( !str_starts_with( $content, '<h2>' ) ) {
        $content = '<h2>' . __( 'Descripción', 'smn' ) . '</h2>' . $content;
    }

    $accordion_content = '';

    $accordion_id = 'accordion-' . uniqid();
    $pattern = '/<h2>(.*?)<\/h2>/i';
    $sections = preg_split($pattern, $content, -1, PREG_SPLIT_DELIM_CAPTURE);
    $sections = array_values(array_filter($sections, function($section) {
        return trim($section) !== '';
    }));

    if (!$sections) {
        return $content;
    }


    $accordion_content .= '<div class="accordion accordion-flush" id="' . $accordion_id . '">';
    
        for ($i = 0; $i < count($sections); $i += 2) {

            $heading = trim($sections[$i]);
            $body = isset($sections[$i + 1]) ? $sections[$i + 1] : '';

            $panel_class = '';
            $btn_class = 'collapsed';
            $aria_expand = 'false';
            $parent = '#' . $accordion_id;

            $accordion_content .= '<div class="accordion-item">
                                    <h2 class="accordion-header" id="heading-' . sanitize_title($heading) . '">
                                        <button class="accordion-button '. $btn_class .'" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-' . sanitize_title($heading) . '" aria-expanded="'.$aria_expand.'" aria-controls="collapse-' . sanitize_title($heading) . '">
                                            ' . esc_html($heading) . '
                                        </button>
                                    </h2>
                                    <div id="collapse-' . sanitize_title($heading) . '" class="accordion-collapse collapse '. $panel_class .'" aria-labelledby="heading-' . sanitize_title($heading) . '" data-bs-parent="' . $parent . '">
                                        <div class="accordion-body">' . $body . '</div>
                                    </div>
                                </div>';
        }

    $accordion_content .= '</div>';

    return $accordion_content;
}

add_filter('woocommerce_get_breadcrumb', 'smn_modify_last_breadcrumb_item');
function smn_modify_last_breadcrumb_item($crumbs) {

        if (!empty($crumbs)) {
            $crumbs[count($crumbs) - 1][0] = '';
        }
        return $crumbs;

}

add_filter( 'woocommerce_breadcrumb_defaults', 'smn_change_breadcrumb_home_text' );
function smn_change_breadcrumb_home_text( $defaults ) {
    $site_title = get_bloginfo( 'name' );
    $defaults['home'] = $site_title;

	return $defaults;
}

remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash');
remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);
add_action( 'woocommerce_before_single_product_summary', 'smn_show_product_tags', 10 );
add_action( 'woocommerce_before_shop_loop_item_title', 'smn_show_product_tags', 10 );
function smn_show_product_tags() {
    global $post, $product;
    $badge_class = 'badge bg-dark text-light';

    $r = '';

    if ( $product->is_on_sale() ) :
        $r .= apply_filters( 'woocommerce_sale_flash', '<span class="' . $badge_class . '">' . esc_html__( 'Sale!', 'woocommerce' ) . '</span>', $post, $product );
    endif;

    // $badge_class = 'badge bg-primary text-light';
    // $tags = wc_get_product_tag_list( $product->get_id(), '</span> <span class="'. $badge_class .'">', '<span class="'. $badge_class .'">', '</span>' );
    // if ( $tags ) {
    //     $r .= strip_tags($tags, '<span>');
    // }

    $texto_destacado_foto = get_field('texto_destacado_foto');
    if ($texto_destacado_foto) {
        $r .= '<span class="badge bg-light text-dark">' . $texto_destacado_foto . '</span>';
    }

    if ( $r ) {
        echo '<div class="product-tags d-flex flex-wrap">' . $r . '</div>';
    }


}

add_filter('woocommerce_loop_add_to_cart_args', 'smn_add_btn_sm_class', 20 );
function smn_add_btn_sm_class($args) {
    $args['class'] .= ' btn-sm';
    $args['class'] = str_replace('btn-outline-primary', 'btn-outline-dark', $args['class']);
    return $args;
}

 // Replace "añadir al carrito" with "Añadir"
 add_filter( 'woocommerce_product_add_to_cart_text', 'smn_add_to_cart_text', 10, 2 );
 function smn_add_to_cart_text( $text, $product ) {

    if ( $product->is_purchasable() && $product->is_in_stock() ) {
        $text = __( 'Añadir al pedido', 'smn-admin' );
    }
    return $text;

}

add_filter( 'woocommerce_catalog_orderby', 'smn_remove_sorting_option_woocommerce_shop' );
function smn_remove_sorting_option_woocommerce_shop( $options ) {
    unset( $options['price'] );   
    unset( $options['price-desc'] );   
    return $options;
}

add_filter( 'woocommerce_catalog_orderby', 'smn_rename_sorting_option_woocommerce_shop' ); 
function smn_rename_sorting_option_woocommerce_shop( $options ) {
   $options['popularity'] = __( 'Más vendidos primero', 'smn' );
   $options['date'] = __( 'Novedades primero', 'smn' );   
   return $options;
}

function smn_split_title( $title, $split_char = '.' ) {

    $split_char = '.';
    if ( !str_contains( $title, $split_char ) ) return $title;

    $title = str_replace( array('mg.', 'ml.'), array('mg', 'ml'), $title );

    $title = explode( $split_char, $title );
    $title = '<span class="title-split">' . implode('.</span> <span class="title-split">', $title ) . '.</span>';

    return $title;

}

add_filter( 'the_title', 'smn_split_title_filter', 10, 2 );
function smn_split_title_filter( $title, $post_id ) {

    if ( is_admin() ) return $title;

    if ( 'product' == get_post_type( $post_id ) && !is_checkout() && !is_cart() ) {

        $title = smn_split_title( $title );

    }

    return $title;

}

// remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
// add_action( 'woocommerce_before_single_product_summary', 'smn_titulo_antes_de_galeria_producto', 1 );
// function smn_titulo_antes_de_galeria_producto() {

//     woocommerce_breadcrumb();

//     echo '<div class="row align-items-center">';

//         echo '<div class="col-md-8 col-lg-9 col-xxl-10">';

//             woocommerce_template_single_title();

//         echo '</div>';

//         echo '<div class="col-md-4 col-lg-3 col-xxl-2 mb-2">';

//             smn_show_brand();

//         echo '</div>';

//     echo '</div>';
    
// }

function smn_mostrar_contenido_bajo_titulo() {

    if ( is_product() ) {
        smn_show_product_terms_badges( 'edad' );
    }

}


// Wrap product tabs in a wrapper section and a container
// add_action( 'woocommerce_after_single_product_summary', 'smn_wrap_before_product_tabs', 5 );
function smn_wrap_before_product_tabs() {
    echo '<section class="product-tabs-wrapper alignfull bg-light py-3 my-3"><div class="container">';
}
// add_action( 'woocommerce_after_single_product_summary', 'smn_wrap_after_product_tabs', 12 );
function smn_wrap_after_product_tabs() {
        echo '</div></section>';
}

function smn_show_brand() {

    echo '<div class="product-brand">';
        echo do_shortcode( '[product_brand]' );
    echo '</div>';

}

function smn_show_logos_after_short_description() {
    $logos = '';
    $marca = do_shortcode( '[product_brand]' );
    if ( $marca ) {
        $logos .= '<div class="product-brand">';
            $logos .= $marca;
        $logos .= '</div>';
    }
    $certificados = get_the_terms( get_the_ID(), 'certificacion' );
    if ( $certificados ) {
        foreach ( $certificados as $certificado ) {
            $icon = get_term_meta( $certificado->term_id, 'thumbnail_id', true );
            if ( $icon ) {
                $logos .= wp_get_attachment_image( $icon, 'medium', false, array( 'class' => 'img-fluid' ) );
            } else {
                $logos .= '<span class="badge bg-light text-dark">' . esc_html( $certificado->name ) . '</span>';
            }
        }
} 
    
    if ( $logos ) {
        echo '<div class="product-logos mb-2 is-layout-flex">';
            echo $logos;
        echo '</div>';
    }
}

add_action( 'woocommerce_after_shop_loop_item_title', 'smn_show_product_sku', 20 );
function smn_show_product_sku() {
    if ( !current_user_can( 'manage_options' )) return;
    
    global $product;
    echo '<span class="sku">' . $product->get_sku() . '</span>';
}

function smn_que_se_dice_sobre() {

    if ( !is_singular('product') ) return;

    $termino_busqueda_wikipedia = get_field('termino_busqueda_wikipedia');
    

}

/* Establecer un importe minimo para poder finalizar el pedido */
function woocommerce_importe_minimo() {

    $minimum = PEDIDO_MINIMO;
    if ( (WC()->cart->subtotal - WC()->cart->tax_total) < $minimum ) {
        if( is_cart() ) {
            wc_print_notice(
                // sprintf( 'El pedido mínimo es de %s (sin impuestos) y tienes %s en el carrito, por favor, añade más productos a tu pedido por valor de %s.' , // Pon aquí el texto que quieras que se muestre en la página de finalizar compra.
                sprintf( 'El pedido mínimo es de %s (sin impuestos). Por favor, añade más productos a tu pedido.' , // Pon aquí el texto que quieras que se muestre en la página de finalizar compra.
                wc_price( $minimum ),
                wc_price( ( WC()->cart->subtotal - WC()->cart->tax_total ) ),
                wc_price( $minimum - ( WC()->cart->subtotal - WC()->cart->tax_total ) )
                ), 'notice'
            );
        } else {
            wc_add_notice(
                // sprintf( 'No puedes finalizar tu compra. El pedido mínimo es de %s (sin impuestos) y tienes %s en el pedido, por favor, añade más productos a tu pedido por valor de %s.' , // Pon aquí el texto que quieras que se muestre en la página de finalizar compra.
                sprintf( 'No puedes finalizar tu compra. El pedido mínimo es de %s (sin impuestos). Por favor, añade más productos a tu pedido.' , // Pon aquí el texto que quieras que se muestre en la página de finalizar compra.
                wc_price( $minimum ),
                wc_price( ( WC()->cart->subtotal - WC()->cart->tax_total ) ),
                wc_price( $minimum - ( WC()->cart->subtotal - WC()->cart->tax_total ) )
                ), 'error'
            );
        }
    }
}
add_action( 'woocommerce_checkout_process', 'woocommerce_importe_minimo' );
add_action( 'woocommerce_before_cart' , 'woocommerce_importe_minimo' );

function disable_checkout_button_no_shipping() { 
    if ( (WC()->cart->subtotal - WC()->cart->tax_total) < PEDIDO_MINIMO ) {
        remove_action('woocommerce_proceed_to_checkout',
              'woocommerce_button_proceed_to_checkout', 20 );
        echo '<a href="" class="btn btn-outline-dark disabled">'. __( 'Debes alcanzar el pedido mínimo para seguir', 'smn' ).'</a>';
    }  
}
add_action('woocommerce_proceed_to_checkout','disable_checkout_button_no_shipping', 1);


add_filter( 'woocommerce_no_shipping_available_html', 'custom_no_shipping_message' );
add_filter( 'woocommerce_cart_no_shipping_available_html', 'custom_no_shipping_message' );
function custom_no_shipping_message( $message ) {
    if ( (WC()->cart->subtotal - WC()->cart->tax_total) < PEDIDO_MINIMO ) {
        $message = __( 'Pedido mínimo no alcanzado', 'smn' );
    }
    return $message;

}

add_action('woocommerce_cart_calculate_fees', 'smn_apply_bulk_discount');
function smn_apply_bulk_discount() {
    global $woocommerce;
    $texto = __('5% descuento por caja completa', 'smn');
    $texto_productos = '';

    $discount = 0;

    foreach ($woocommerce->cart->get_cart() as $cart_item_key => $cart_item) {
        $product_id = $cart_item['product_id'];
        // $promocion_caja_completa = get_field('promocion_caja_completa', $product_id);
        $promocion_caja_completa = has_term( DESCUENTO_5_CAJA_TERM_ID, 'product_tag', $product_id);

        $unidades_por_caja = get_field('unidades_por_caja', $product_id);

        if ($promocion_caja_completa && $cart_item['quantity'] >= $unidades_por_caja) {
            $line_total = $cart_item['line_total'];
            $discount += $line_total * 0.05;
            // $texto_productos .= ' ' . get_the_title($product_id);
        }
    }

    if ($discount > 0) {
        // if ( $texto_productos ) {
        //     $texto .= '<br><small>' . __( 'Aplica a', 'smn' ) . $texto_productos . '</small>';
        // }
        $woocommerce->cart->add_fee( $texto, -$discount);
    }
}

add_filter('woocommerce_cart_item_name', 'smn_add_discount_text_to_cart_item_name', 10, 3);
function smn_add_discount_text_to_cart_item_name($item_name, $cart_item, $cart_item_key) {
    $product_id = $cart_item['product_id'];
    // $promocion_caja_completa = get_field('promocion_caja_completa', $product_id);
    $promocion_caja_completa = has_term( DESCUENTO_5_CAJA_TERM_ID, 'product_tag', $product_id);

    $unidades_por_caja = get_field('unidades_por_caja', $product_id);

    if ($promocion_caja_completa && $cart_item['quantity'] >= $unidades_por_caja) {
        $item_name .= ' <span class="badge bg-light text-dark">' . __('5% descuento por caja completa', 'smn') . '</span>';
    }

    return $item_name;
}

add_action('woocommerce_checkout_before_customer_details', 'smn_custom_checkout_field', 5);
function smn_custom_checkout_field() {

    $checkout = WC()->checkout;

    $logo_id = smn_get_current_user_avatar();
    $btn_text = __( 'Subir mi logo', 'smn' );
    $custom_brand_value = ($logo_id) ? '1' : '0';

    echo '<div class="woocommerce-additional-fields" id="checkout-field-personalizar-marca">';
        echo '<h4>' . __('Personalización de marca') . '</h4>';

        echo '<div class="row">';

            echo '<div class="col-md-6">';

                woocommerce_form_field('custom_branding', array(
                    'type' => 'checkbox',
                    'class' => array('form-row-wide'),
                    'label' => __('¿Deseas recibir el pedido con tu marca personalizada?'),
                    'default' => $custom_brand_value,
                ), $checkout->get_value( 'custom_branding' ) );

                woocommerce_form_field('custom_branding_img_id', array(
                    'type' => 'hidden',
                    'class' => array('form-row-wide'),
                    'default' => $logo_id,
                ), $checkout->get_value( 'custom_branding_img_id' ) );

            echo '</div>';

            echo '<div class="col-md-6">';

                if ( $logo_id ) {
                    echo wpautop( __( 'Este es tu logotipo actual:', 'smn' ) );
                    $btn_text = __( 'Cambiar mi logo', 'smn' );
                } else {
                    echo wpautop( __( 'Aún no has subido tu logotipo personalizado.', 'smn' ) );
                }


        
                echo wp_get_attachment_image( $logo_id, 'medium', false, array( 'class' => 'img-fluid mb-2' ) );
                echo '<p><a class="btn btn-sm btn-outline-dark" href='.wc_get_page_permalink( 'myaccount' ).'">'.$btn_text.'</a></p>';

            echo '</div>';

        echo '</div>';

    echo '</div>';

}

add_action('woocommerce_checkout_update_order_meta', 'smn_save_custom_checkout_field');
function smn_save_custom_checkout_field($order_id) {

    error_log(print_r($_POST, true));

    if (!empty($_POST['custom_branding'])) {
        $order = wc_get_order( $order_id );
        $order->update_meta_data( 'custom_branding', sanitize_text_field( $_POST['custom_branding'] ) );
        $order->save_meta_data();
    }

    if (!empty($_POST['custom_branding_img_id'])) {
        $order = wc_get_order( $order_id );
        $order->update_meta_data( 'custom_branding_img_id', sanitize_text_field( $_POST['custom_branding_img_id'] ) );
        $order->save_meta_data();
    }

}

add_action( 'woocommerce_order_details_after_order_table', 'smn_display_custom_checkout_field_in_thankyou', 10, 1 );
function smn_display_custom_checkout_field_in_thankyou( $order_id ) {
    $order = wc_get_order( $order_id );
    $custom_branding = $order->get_meta( 'custom_branding', true );
    $custom_branding_text = $custom_branding ? 'Sí' : 'No';

    echo '<div class="card card-body">';
        echo '<div class="d-flex gap-2 flex-wrap">';
            echo '<p><strong>' . __('Marca personalizada en el envase:') . '</strong> ' . $custom_branding_text . '</p>';
            if ( $custom_branding ) {
                $logo_id = $order->get_meta( 'custom_branding_img_id', true );
                if ($logo_id) {
                    echo '<div>';
                        echo '<p><strong>' . __('Tu marca:') . '</strong></p>';
                        echo wp_get_attachment_image( $logo_id, 'medium', false, array( 'class' => 'img-fluid', 'style' => 'height: 100px; width: auto;' ) );
                    echo '</div>';
                }
            }
        echo '</div>';
    echo '</div>';
}

add_action('woocommerce_admin_order_data_after_billing_address', 'smn_display_custom_checkout_field_in_admin', 10, 1);
function smn_display_custom_checkout_field_in_admin($order) {
    $custom_branding = $order->get_meta( 'custom_branding', true );
    $custom_branding_text = $custom_branding ? 'Sí' : 'No';
    echo '<p><strong>' . __('Marca personalizada en el envase:') . '</strong> ' . $custom_branding_text . '</p>';
    if ( $custom_branding ) {
        $logo_id = $order->get_meta( 'custom_branding_img_id', true );
        if ($logo_id) {
            echo '<p><strong>' . __('Tu marca:') . '</strong></p>';
            echo wp_get_attachment_image( $logo_id, 'medium', false, array( 'class' => 'img-fluid' ) );
        }
    }
}

add_action( 'woocommerce_email_after_order_table', 'smn_display_custom_checkout_field_in_email', 10, 4 );
function smn_display_custom_checkout_field_in_email( $order, $sent_to_admin, $plain_text, $email ) {
    $custom_branding = $order->get_meta( 'custom_branding', true );
    $custom_branding_text = $custom_branding ? 'Sí' : 'No';
    echo '<p><strong>' . __('Marca personalizada en el envase:') . '</strong> ' . $custom_branding_text . '</p>';
    $logo_id = $order->get_meta( 'custom_branding_img_id', true );
    if ( $custom_branding ) {
        if ($logo_id) {
            echo '<p><strong>' . __('Tu marca:') . '</strong></p>';
            echo wp_get_attachment_image( $logo_id, 'medium', false, array( 'class' => 'img-fluid' ) );
        }
    }
}

add_filter( 'woocommerce_checkout_fields', 'smn_checkout_fields_readonly', 9999 );
function smn_checkout_fields_readonly( $fields ) {

    foreach ( $fields['billing'] as $field_key => $field_atts ) {
    if ( isset($fields['billing'][$field_key]['type']) && $fields['billing'][$field_key]['type'] == 'hidden' ) {
        continue;
    }
    $fields['billing'][$field_key]['type'] = 'text';
    $fields['billing'][$field_key]['required'] = 0;
    $fields['billing'][$field_key]['custom_attributes'] = array( 
        'readonly'      => 'readonly',
        'disabled'      => 'disabled',
    );
    $fields['billing'][$field_key]['input_class'][] = 'form-control-plaintext';
    $fields['billing'][$field_key]['input_class'][] = 'disabled';
    $fields['billing'][$field_key]['class'][] = 'opacity-25';
   }
   
   return $fields;

}

add_filter( 'woocommerce_form_field' , 'remove_checkout_optional_fields_label', 10, 4 );
function remove_checkout_optional_fields_label( $field, $key, $args, $value ) {
    // Only on checkout page
    if( is_checkout() && ! is_wc_endpoint_url() ) {
        $optional = '&nbsp;<span class="optional">(' . esc_html__( 'optional', 'woocommerce' ) . ')</span>';
        $field = str_replace( $optional, '', $field );
    }
    return $field;
}

add_action('woocommerce_before_checkout_billing_form', 'smn_add_text_after_billing_title');
function smn_add_text_after_billing_title() {
    echo '<p class="woocommerce-billing-details-text">' . __('Si quieres cambiar alguno de tus datos de facturación, por favor contacta con nosotros.', 'smn') . '</p>';
    echo '<p><a class="btn btn-sm btn-outline-dark" target="_blank" href="' . get_permalink(CONTACT_ID) . '">' . __('Contactar', 'smn') . '</a></p>';
}


// Sticky product gallery
add_action( 'woocommerce_before_single_product_summary', 'smn_sticky_product_gallery_wrapper', 15 );
function smn_sticky_product_gallery_wrapper() {
    echo '<div class="sticky-product-gallery-wrapper clearfix">';
}

add_action( 'woocommerce_after_single_product_summary', 'smn_sticky_product_gallery_close', 1 );
function smn_sticky_product_gallery_close() {
    echo '</div>';
}

/**
 * Override loop template and show quantities next to add to cart buttons
 */
add_filter( 'woocommerce_loop_add_to_cart_link', 'quantity_inputs_for_woocommerce_loop_add_to_cart_link', 10, 2 );
function quantity_inputs_for_woocommerce_loop_add_to_cart_link( $html, $product ) {
	if ( $product && $product->is_type( 'simple' ) && $product->is_purchasable() && $product->is_in_stock() && ! $product->is_sold_individually() ) {
		$html = '<form action="' . esc_url( $product->add_to_cart_url() ) . '" class="cart" method="post" enctype="multipart/form-data">';
            $html .= woocommerce_quantity_input( array(), $product, false );
            $html .= '<button type="submit" class="add_to_cart_button btn btn-outline-dark btn-sm">' . esc_html( $product->add_to_cart_text() ) . '</button>';
		$html .= '</form>';
	}
	return $html;
}