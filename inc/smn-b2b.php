<?php 

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

add_action('woocommerce_single_product_summary', 'smn_show_pvp', 22);
add_action('woocommerce_single_product_summary', 'smn_texto_caja_completa', 32);
// add_action('woocommerce_single_product_summary', 'smn_texto_marca_personalizada', 45);


add_action( 'widgets_init', function() {

	register_sidebar(
		array(
			'name'          => __( 'Área de registro', 'smn-admin' ),
			'id'            => 'register-area',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<p class="widget-title">',
			'after_title'   => '</p>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Texto explicativo marca personalizada en la ficha de producto', 'smn-admin' ),
			'id'            => 'marca-personalizada',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<p class="widget-title">',
			'after_title'   => '</p>',
		)
	);

} );

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

add_filter( 'woocommerce_catalog_orderby', 'smn_remove_sorting_option_woocommerce_shop' );
function smn_remove_sorting_option_woocommerce_shop( $options ) {
    unset( $options['price'] );   
    unset( $options['price-desc'] );   
    return $options;
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

