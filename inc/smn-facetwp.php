<?php 
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

function fwp_wrapper_open() {
  if ( ! is_singular() ) : echo '<div class="facetwp-template"><div class="facetwp-template__inner">'; endif;
}
function fwp_wrapper_close() {
  if ( ! is_singular() ) : echo '</div></div><!-- end facetwp-template -->'; endif;
}
add_action( 'woocommerce_before_shop_loop', 'fwp_wrapper_open', 5 );
add_action( 'woocommerce_after_shop_loop', 'fwp_wrapper_close', 15 );
add_action( 'woocommerce_no_products_found', 'fwp_wrapper_open', 5 );
add_action( 'woocommerce_no_products_found', 'fwp_wrapper_close', 15 );

function fwp_add_facet_labels() {
    if ( !function_exists( 'facetwp_display' ) ) return false;
  ?>
    <script>
      (function($) {
        $(document).on('facetwp-loaded', function() {
          $('.facetwp-facet').each(function() {
            var facet = $(this);
            var facet_name = facet.attr('data-name');
            var facet_type = facet.attr('data-type');
            var facet_label = FWP.settings.labels[facet_name];
            if (facet_type !== 'pager' && facet_type !== 'sort' && facet_type !== 'search' && facet_type !== 'reset') {
              if (facet.closest('.facet-wrap').length < 1 && facet.closest('.facetwp-flyout').length < 1) {
                facet.wrap('<div class="facet-wrap"></div>');
                facet.before('<label class="facet-label">' + facet_label + '</label>');
              }
            }
          });
        });
      })(jQuery);
    </script>
  <?php
}
add_action( 'wp_head', 'fwp_add_facet_labels', 100 );

add_action( 'wp_head', function() { ?>

  <script>
      (function($) {
          $(document).on('facetwp-refresh', function() {
              if ( FWP.soft_refresh == true ) {
                  FWP.enable_scroll = true;
              } else {
                  FWP.enable_scroll = false;
              }
          });
          $(document).on('facetwp-loaded', function() {
              if ( FWP.enable_scroll == true ) {
                  $('html, body').animate({ scrollTop: 0 }, 500);
              }
          });
      })(jQuery);
  </script>

<?php } );

add_action( 'wp_head', function() {
    ?>
      <script>
        (function($) {
          $(function() {
            if ('object' != typeof FWP) return;
   
            /* Modify each facet's wrapper HTML */
            FWP.hooks.addFilter('facetwp/flyout/facet_html', function(facet_html) {
              return facet_html.replace('<h3>{label}</h3>', '<label>{label}</label>');
            });
          });
        })(jQuery);
      </script>
    <?php
  }, 100 );

  add_action( 'wp_footer', function() {
    ?>
      <script>
        (function($) {
          document.addEventListener('facetwp-loaded', function() {
            $.each(FWP.settings.num_choices, function(key, val) {
   
              // assuming each facet is wrapped within a "facet-wrap" container element
              // this may need to change depending on your setup, for example:
              // change ".facet-wrap" to ".widget" if using WP text widgets
   
              var $facet = $('.facetwp-facet-' + key);
              var $wrap = $facet.closest('.facet-wrap');
              var $flyout = $facet.closest('.flyout-row');
              if ($wrap.length || $flyout.length) {
                var $which = $wrap.length ? $wrap : $flyout;
                (0 === val) ? $which.hide() : $which.show();
              }
            });
          });
        })(jQuery);
      </script>
    <?php
  }, 100 );

add_filter( 'facetwp_assets', function( $assets ) {
    FWP()->display->json['expand'] = '<span class="facetwp-expand-icon-expand">+</span>';
    FWP()->display->json['collapse'] = '<span class="facetwp-expand-icon-collapse">-</span>';

    // Return the same thing (since we're hijacking this hook)
    return $assets;
});

add_action( 'facetwp_scripts', function() {
  ?>
    <script>
      (function($) {
   
        // On start of the facet refresh, but not on first page load
        $(document).on('facetwp-refresh', function() {
          if ( FWP.loaded ) {
            $('.facetwp-template').prepend('<div class="spinner d-flex justify-content-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
            $('.facetwp-template').addClass('facetwp-loading');
          }
        });
   
        // On finishing the facet refresh
        $(document).on('facetwp-loaded', function() {
          $('.facetwp-template .spinner').remove();
          $('.facetwp-template').removeClass('facetwp-loading');
        });
        
      })(jQuery);
    </script>
  <?php
}, 100 );

// output only sub categories of current woocommerce product category
add_filter( 'facetwp_facet_render_args', function( $args ) {

  if ( 'product_cat' == $args['facet']['name'] && is_product_category() ) {  // replace 'my_facet_name' with the name of your facet

      $current_term = get_queried_object_id();

      // $args['facet']['parent_term'] = $current_term;

      foreach ( $args['values'] as $key => $row ) {
          /* to also include current term in chocies add this to if:
             && $row['term_id'] != $current_term
          */
          if ( $row['parent_id'] != $current_term ) {
              // unset( $args['values'][$key] );
          }
      }

      $args['values'] = array_values( $args['values'] ); // fixes indexes

      // if ( current_user_can( 'manage_options' ) ) :
      //   echo '<pre>';
      //     print_r ( $args );
      //   echo '</pre>';
      // endif;
    
    }

  return $args;
});