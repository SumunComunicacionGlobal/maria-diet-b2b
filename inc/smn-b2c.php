<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Si está activada la opción de envío gratuito, se muestra solo el método de envío gratuito
add_filter( 'woocommerce_package_rates', 'smn_b2c_filter_free_shipping_only', 100, 2 );

function smn_b2c_filter_free_shipping_only( $rates, $package ) {
    $free_shipping = array();
    foreach ( $rates as $rate_id => $rate ) {
        if ( 'free_shipping' === $rate->method_id ) {
            $free_shipping[ $rate_id ] = $rate;
            break;
        }
    }
    return ! empty( $free_shipping ) ? $free_shipping : $rates;
}

// cambiar el texto (incl. impusetos) de la caja totales del carrito por (IVA incluido)
add_filter( 'woocommerce_countries_inc_tax_or_vat', 'smn_b2c_change_cart_totals_text' );
function smn_b2c_change_cart_totals_text( $text ) {

    if ( $text ==  __( '(incl. VAT)', 'woocommerce' ) || $text == __( '(incl. tax)', 'woocommerce' ) ) {
        $text = __( '(IVA incluido)', 'smn' );
    }
    return $text;
}