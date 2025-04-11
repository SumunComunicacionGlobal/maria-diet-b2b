<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function understrap_posted_on() {

    $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
    if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
        $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
    }
    $time_string = sprintf( $time_string,
        esc_attr( get_the_date( 'c' ) ),
        esc_html( get_the_date() )
    );
    echo $time_string; // WPCS: XSS OK.

}



/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function understrap_entry_footer() {
	// Hide category and tag text for pages.
	if ( is_singular( 'post') ) {
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( esc_html__( ', ', 'understrap' ) );
		if ( $categories_list && understrap_categorized_blog() ) {
			/* translators: %s: Categories of current post */
			printf( '<span class="cat-links">' . esc_html__( 'Posted in %s', 'understrap' ) . '</span>', $categories_list ); // WPCS: XSS OK.
		}
		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', esc_html__( ', ', 'understrap' ) );
		if ( $tags_list ) {
			/* translators: %s: Tags of current post */
			printf( '<span class="tags-links">' . esc_html__( 'Tagged %s', 'understrap' ) . '</span>', $tags_list ); // WPCS: XSS OK.
		}
	}
	// if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
	// 	echo '<span class="comments-link">';
	// 	comments_popup_link( esc_html__( 'Leave a comment', 'understrap' ), esc_html__( '1 Comment', 'understrap' ), esc_html__( '% Comments', 'understrap' ) );
	// 	echo '</span>';
	// }
	edit_post_link(
		sprintf(
			/* translators: %s: Name of current post */
			esc_html__( 'Edit %s', 'understrap' ),
			the_title( '<span class="screen-reader-text">"', '"</span>', false )
		),
		'<span class="edit-link">',
		'</span>'
	);
}

function smn_get_breadcrumb() {

	if ( is_front_page() ) return false;

	ob_start();

	if(function_exists('bcn_display')) {
		echo '<div class="breadcrumb" typeof="BreadcrumbList" vocab="https://schema.org/">';
			echo '<div class="breadcrumb-inner">';
				bcn_display();
			echo '</div>';
		echo '</div>';
	} elseif ( function_exists( 'rank_math_the_breadcrumbs') ) {
		echo '<div class="breadcrumb">';
			echo '<div class="breadcrumb-inner">';
				rank_math_the_breadcrumbs(); 
			echo '</div>';
		echo '</div>';
	} elseif ( function_exists('yoast_breadcrumb') ) {
		yoast_breadcrumb( '<div id="breadcrumbs" class="breadcrumb"><div class="breadcrumb-inner">','</div></div>' );
	}

	$r = ob_get_clean();

	if ( $r ) {
		return '<div class="breadcrumb-wrapper py-1">' . $r . '</div>';
	}

}

function smn_breadcrumb() {
	
	$r = smn_get_breadcrumb();

	if ( $r ) {
		echo '<div class="container">';
			echo $r;
		echo '</div>';
	}

}

function smn_get_navbar_class() {

	$navbar_class = 'bg-white navbar-light';

	if ( is_singular() ) {

		$navbar_bg = get_post_meta( get_the_ID(), 'navbar_bg', true );

		switch ($navbar_bg) {
			case 'navbar-light':
				$navbar_class = 'bg-light navbar-light';
				break;

			case 'transparent':
				$navbar_class = 'navbar-dark';
				break;
			
		}
		
	}

	return $navbar_class;

}

function smn_banner( $title, $text, $term_link, $post_link, $tag_text = null, $img_id = null, $btn_text = null ) { 
	
	$link = false;
	$background_dim = '100';
	if ( $img_id ) {
		$background_dim = '80';
	}
	
	?>

	<div class="wp-block-cover promo">

		<span aria-hidden="true" class="wp-block-cover__background has-primary-background-color has-background-dim-<?php echo $background_dim; ?> has-background-dim"></span>

		<?php if ( $img_id ) { 
			echo wp_get_attachment_image( $img_id, 'large', false, array( 'class' => 'wp-block-cover__image-background' ) );
		} ?>

		<div class="wp-block-cover__inner-container">

			<?php if ( $tag_text ) { ?>
				<p class="mb-1"><span class="badge bg-dark"><?php echo $tag_text; ?></span></p>
			<?php } ?>

			<p class="h3 mb-0"><?php echo $title; ?></p>

			<?php if ( $text ) { ?>
				<p class="lead"><?php echo $text; ?></p>
			<?php } ?>

			<?php if ( $term_link ) { 
				$link = get_term_link( $term_link );
			} elseif ( $post_link ) {
				$link = get_the_permalink( $post_link );
			} ?>

			<?php if ( $link ) { ?>
				<a href="<?php echo $link; ?>" class="btn btn-outline-light"><?php echo $btn_text ? $btn_text : __( 'Ver más', 'smn' ); ?></a>
			<?php } ?>

		</div>

	</div>

	<?php

}

function smn_get_back_button() {

	$back = '';
	// $back = '<a href="javascript:history.back()" class="btn btn-sm btn-outline-light">' . __( 'Volver', 'smn' ) . '</a>';
	if ( is_product_category() ) {
		$current_term = get_queried_object();
		if ( $current_term && $current_term->parent ) {
			$parent_term = get_term( $current_term->parent, 'product_cat' );
			if ( $parent_term && ! is_wp_error( $parent_term ) ) {
				$back = '<a href="' . get_term_link( $parent_term ) . '" class="btn btn-sm btn-outline-light">' . sprintf( __( 'Volver a %s', 'smn' ), $parent_term->name ) . '</a>';
			}
		} else {
			// $back = '<a href="' . get_permalink( wc_get_page_id( 'shop' ) ) . '" class="btn btn-sm btn-outline-light">' . __( 'Volver a la tienda', 'smn' ) . '</a>';
			$back = '<a href="' . get_home_url(null, '#productos') . '" class="btn btn-sm btn-outline-light">' . __( 'Volver al inicio', 'smn' ) . '</a>';
		}
	}

	return $back;
}