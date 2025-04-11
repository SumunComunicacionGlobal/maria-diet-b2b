<?php
/**
 * Declaring widgets
 *
 * @package understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

function understrap_widgets_init() {
	
	register_sidebar(
		array(
			'name'          => __( 'Top bar', 'smn-admin' ),
			'id'            => 'top-bar',
			'description'   => __( 'Top bar widget area', 'understrap' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s col-6">',
			'after_widget'  => '</aside>',
			'before_title'  => '<p class="widget-title">',
			'after_title'   => '</p>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Mensaje fijo en la página de inicio', 'smn-admin' ),
			'id'            => 'header-promos',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<p class="widget-title">',
			'after_title'   => '</p>',
		)
	);

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

	register_sidebar(
		array(
			'name'          => __( 'Ventajas', 'smn-admin' ),
			'id'            => 'advantages',
			'before_widget' => '<aside id="%1$s" class="widget %2$s col-6">',
			'after_widget'  => '</aside>',
			'before_title'  => '<p class="widget-title">',
			'after_title'   => '</p>',
		)
	);

	register_sidebar(
		array(
			/* translators: Widget area title */
			'name'          => __( 'Filtros de productos sidebar', 'smn-admin' ),
			'id'            => 'left-sidebar',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<p class="widget-title">',
			'after_title'   => '</p>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Right Sidebar', 'understrap' ),
			'id'            => 'right-sidebar',
			'description'   => __( 'Right sidebar widget area', 'understrap' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<p class="widget-title">',
			'after_title'   => '</p>',
		)
	);

    register_sidebar(
        array(
            'name'          => __( 'Pre footer', 'understrap' ),
            'id'            => 'prefooter',
            'description'   => __( 'Aparece antes del Pie de Página Completo', 'understrap' ),
            'before_widget' => '<div id="%1$s" class="footer-widget col-md-6 %2$s">',
            'after_widget'  => '</div><!-- .footer-widget -->',
            'before_title'  => '<p class="widget-title">',
            'after_title'   => '</p>',
        )
    );

	register_sidebar(
		array(
			'name'          => __( 'Footer Full', 'understrap' ),
			'id'            => 'footerfull',
			'description'   => __( 'Full sized footer widget with dynamic grid', 'understrap' ),
			'before_widget' => '<div id="%1$s" class="footer-widget %2$s dynamic-classes">',
			'after_widget'  => '</div><!-- .footer-widget -->',
			'before_title'  => '<p class="widget-title">',
			'after_title'   => '</p>',
		)
	);

}

