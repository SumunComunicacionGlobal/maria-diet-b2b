<?php 

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

function contenido_pagina($atts) {
	extract( shortcode_atts(
		array(
				'id' 	=> 0,
				'imagen'	=> 'no',
				'dominio'	=> false,

		), $atts)	
	);
	if ($dominio) {
		$api_url = $dominio . '/wp-json/wp/v2/pages/' . $id;
		$data = wp_remote_get( $api_url );
		$data_decode = json_decode( $data['body'] );

		// echo '<pre>'; print_r($data_decode); echo '</pre>';

		$content = $data_decode->content->rendered;
		return $content;
	} else {
		if ( 0 != $id) {
			$content_post = get_post($id);
			$content = $content_post->post_content;
			$content = '<div class="post-content-container">'.apply_filters('the_content', $content) .'</div>';
			if ('si' == $imagen) {
				$content = '<div class="entry-thumbnail">'.get_the_post_thumbnail($id, 'full') . '</div>' . $content;
			}
			return $content;
		}
	}
}
add_shortcode('contenido_pagina','contenido_pagina');

function year_shortcode() {
  $year = date('Y');
  return $year;
}
add_shortcode('year', 'year_shortcode');

function paginas_hijas() {
	global $post;
	if ( is_post_type_hierarchical( $post->post_type ) /*&& '' == $post->post_content */) {
		$args = array(
			'post_type'			=> array($post->post_type),
			'posts_per_page'	=> -1,
			'post_status'		=> 'publish',
			'orderby'			=> 'menu_order',
			'order'				=> 'ASC',
			'post_parent'		=> $post->ID,
		);
		$r = '';
		$query = new WP_Query($args);
		if ($query->have_posts() ) {
			$r .= '<div class="contenido-adicional mt-5">';
			// $r .= '<h3>'.__( 'Contenido en', 'smn' ).' "'.$post->post_title.'"</h3>';
			// $r .= '<ul>';
			while($query->have_posts() ) {
				$query->the_post();
				// $r .= '<li>';
					$r .= '<a class="btn btn-primary btn-lg me-2 mb-2 pagina-hija" href="'.get_permalink( get_the_ID() ).'" title="'.get_the_title().'" role="button" aria-pressed="false">'.get_the_title().'</a>';
				$r .= '</li>';
			}
			// $r .= '</ul>';
			// $r .= '</div>';
		} elseif(0 != $post->post_parent) {
			wp_reset_postdata();
			$current_post_id = get_the_ID();
			$args['post_parent'] = $post->post_parent;
			$query = new WP_Query($args);
			if ($query->have_posts() && $query->found_posts > 1 ) {
				$r .= '<div class="contenido-adicional">';
				while($query->have_posts() ) {
					$query->the_post();
					if ($current_post_id == get_the_ID()) {
						$r .= '<span class="btn btn-primary btn-sm me-2 mb-2">'.get_the_title().'</span>';
					} else {
						$r .= '<a class="btn btn-outline-primary btn-sm me-2 mb-2" href="'.get_permalink( get_the_ID() ).'" title="'.get_the_title().'" role="button" aria-pressed="false">'.get_the_title().'</a>';
					}
				}
				$r .= '</div>';
			}
		}
		wp_reset_postdata();
		return $r;
	}
}
add_shortcode( 'paginas_hijas', 'paginas_hijas' );

// add_filter('the_content', 'mostrar_paginas_hijas', 100);
function mostrar_paginas_hijas($content) {
	global $post;
	if (is_admin() || !is_singular() || !in_the_loop() || is_front_page() ) return $content;
	global $post;
	if (has_shortcode( $post->post_content, 'paginas_hijas' )) return $content;

	return $content . paginas_hijas();

}

function sitemap() {
	$pt_args = array(
		'has_archive'		=> true,
	);
	$pts = get_post_types( $pt_args );
	// if (isset($pts['rl_gallery'])) unset $pts['rl_gallery'];
	$pts = array_merge( array('page'), $pts, array('post') );
	$r = '';

	foreach ($pts as $pt) {
		$pto = get_post_type_object( $pt );
		$taxonomies = get_object_taxonomies( $pt );

		$posts_args = array(
				'post_type'			=> $pt,
				'posts_per_page'	=> -1,
				'orderby'			=> 'menu_order',
				'order'				=> 'asc',
		);

		$posts_q = new WP_Query($posts_args);
		if ($posts_q->have_posts()) {

			$r .= '<h3 class="mt-3">'.$pto->labels->name.'</h3>';
			if ($taxonomies) {
				foreach ($taxonomies as $tax) {
					$terms = get_terms( array('taxonomy' => $tax) );
					foreach ($terms as $term) {
						$r .= '<a href="'.get_term_link( $term ).'" class="btn btn-dark btn-sm me-1 mb-1">'.$term->name.'</a>';
					}
				}
			}

			while ($posts_q->have_posts()) { $posts_q->the_post();
				$r .= '<a href="'.get_the_permalink().'" class="btn btn-outline-primary btn-sm me-1 mb-1">'.get_the_title().'</a>';
			}
		}

		wp_reset_postdata();
	}

	return $r;
}
add_shortcode( 'sitemap', 'sitemap' );

function testimonios() {
	ob_start();
	get_template_part( 'global-templates/carousel-testimonios' );
	$r = ob_get_clean();

	return $r;
}
add_shortcode( 'testimonios', 'testimonios' );

function smn_get_reusable_block( $block_id = '' ) {
    if ( empty( $block_id ) || (int) $block_id !== $block_id ) {
        return;
    }
    $content = get_post_field( 'post_content', $block_id );
    return apply_filters( 'the_content', $content );
}

function smn_reusable_block( $block_id = '' ) {
    echo smn_get_reusable_block( $block_id );
}

function smn_reusable_block_shortcode( $atts ) {
    extract( shortcode_atts( array(
        'id' => '',
    ), $atts ) );
    if ( empty( $id ) || (int) $id !== $id ) {
        return;
    }
    $content = smn_get_reusable_block( $id );
    return $content;
}
add_shortcode( 'reusable', 'smn_reusable_block_shortcode' );

function sumun_shortcode_subcategorias() {
	ob_start();
	get_template_part( 'global-templates/subcategories' );
	$r = ob_get_clean();

	return $r;
}
add_shortcode( 'subcategorias', 'sumun_shortcode_subcategorias' );

function sumun_shortcode_blog() {
	ob_start();
	get_template_part( 'global-templates/blog' );
	$r = ob_get_clean();

	return $r;
}
add_shortcode( 'blog', 'sumun_shortcode_blog' );

function sumun_shortcode_casos_de_exito() {
	ob_start();
	get_template_part( 'global-templates/casos-de-exito' );
	$r = ob_get_clean();

	return $r;
}
add_shortcode( 'casos_de_exito', 'sumun_shortcode_casos_de_exito' );

function smn_product_categories_tabs() {
	ob_start();
	get_template_part( 'global-templates/product-categories-tabs' );
	$r = ob_get_clean();

	return $r;
}
add_shortcode( 'categorias_principales', 'smn_product_categories_tabs' );

function sumun_shortcode_ventajas( $atts ) {

	extract( shortcode_atts( array(
        'textos' => '',
    ), $atts ) );

	ob_start();
	get_template_part( 'global-templates/ventajas', null, array('textos' => $textos) );
	$r = ob_get_clean();

	return $r;
}
add_shortcode( 'ventajas', 'sumun_shortcode_ventajas' );

add_shortcode( 'breadcrumb', 'smn_get_breadcrumb' );
add_shortcode( 'breadcrumbs', 'smn_get_breadcrumb' );

function boton_desplegable_categorias( $atts ) {
	extract( shortcode_atts( array(
		'term_id' => '',
	), $atts ) );

	if ( empty( $term_id ) || !is_numeric( $term_id ) ) {
		return;
	}

	$term = get_term( $term_id );
	if ( is_wp_error( $term ) ) {
		return;
	}

	$child_terms = get_terms( array(
		'taxonomy' => $term->taxonomy,
		'parent'   => $term_id,
		'hide_empty' => false,
	) );

	if ( empty( $child_terms ) || is_wp_error( $child_terms ) ) {
		return;
	}
	ob_start(); ?>

	<div class="collapse" id="collapseSubcategories<?php echo $term_id; ?>" aria-labelledby="collapseMenuButton<?php echo $term_id; ?>">
		<ul class="collapse-subcategories-list">
			<?php foreach ( $child_terms as $child_term ) : ?>
				<li><?php echo esc_html( $child_term->name ); ?></li>
			<?php endforeach; ?>
		</ul>
	</div>

	<div class="text-end mt-3">
		<a href="#collapseSubcategories<?php echo $term_id; ?>" class="btn btn-outline-light collapse-menu-button" id="collapseMenuButton<?php echo $term_id; ?>" role="button" data-bs-toggle="collapse" aria-expanded="false">
			<?php echo __( 'Ver categorías', 'smn' ); ?>
		</a>
	</div>

	<script>
		jQuery(document).ready(function($) {

			var collapseButton = $('#collapseMenuButton<?php echo $term_id; ?>');
			var collapseElement = $(collapseButton.attr('href'));
			var coverBackground = collapseButton.closest('.wp-block-cover');
			const coverBackgroundClass = 'wp-block-cover-collapsed';

			if (coverBackground.length) {

				coverBackground.addClass(coverBackgroundClass);

			}

			collapseElement.on('show.bs.collapse', function() {
				if (coverBackground.length) {
					coverBackground.removeClass(coverBackgroundClass);
				}
			});

			collapseElement.on('hide.bs.collapse', function() {
				if (coverBackground.length) {
					coverBackground.addClass(coverBackgroundClass);
				}
			});

		});
	</script>
	<?php $output = ob_get_clean();

	return $output;
}
add_shortcode( 'boton_desplegable_categorias', 'boton_desplegable_categorias' );

function nombre_cliente_shortcode() {
	$current_user = wp_get_current_user();
	if ($current_user->exists() && !empty($current_user->display_name)) {
		return $current_user->display_name;
	} else {
		return get_bloginfo('name');
	}
}
add_shortcode('nombre_cliente', 'nombre_cliente_shortcode');

function categorias_cliente_shortcode() {
	$current_user = wp_get_current_user();
	if (!$current_user->exists()) {
		return '';
	}

	// No usar 'fields' => 'names' porque el plugin Hide Product Cats está interfiriendo en la consulta
	$terms = get_terms(array(
		'taxonomy'   => 'product_cat',
		'parent'     => 0,
		'hide_empty' => true,
	));

	if (empty($terms) || is_wp_error($terms)) {
		return '';
	}

	$output_array = array();

	foreach ($terms as $key => $term) {
		$output_array[] = '<span>' . $term->name . '</span>';
	}

	$output = implode(' · ', $output_array);

	return $output;
}
add_shortcode('categorias_cliente', 'categorias_cliente_shortcode');

function marcas_shortcode( $atts ) {

	extract( shortcode_atts( array(
		'exclude' => '',
	), $atts ) );

	$args = array(
		'taxonomy'   => 'product_brand',
		'hide_empty' => false,
	);

	if ( $exclude ) {
		$exclude = explode( ',', $exclude );
		$args['exclude'] = $exclude;
	}

	$terms = get_terms( $args );

	if (empty($terms) || is_wp_error($terms)) {
		return '';
	}

	$output = '<div class="slick-marcas-carousel slick-padded">';

	foreach ($terms as $term) {
		$thumbnail_id = get_term_meta($term->term_id, 'thumbnail_id', true);
		$term_link = get_term_link($term);

		if (!is_wp_error($term_link)) {
			// $output .= '<div class="col-6 col-md-3 mb-2 text-center">';
			$output .= '<a href="' . esc_url($term_link) . '">';
				if ( $thumbnail_id ) {
					$output .= wp_get_attachment_image( $thumbnail_id, 'medium', false, array('class' => 'img-fluid') );
				} else {
					$output .= '<p class="h5 mt-2">' . esc_html($term->name) . '</p>';
				}
			$output .= '</a>';
			// $output .= '</div>';
		}
	}

	$output .= '</div>';

	return $output;
}
add_shortcode('marcas', 'marcas_shortcode');