<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$terms = get_terms( array( 
	'taxonomy' 		=> 'product_cat', 
	'parent' 		=> 0, 
	'hide_empty' 	=> true,
) );

if ( $terms ) { ?>

	<section id="productos" class="product-categories-tabs my-3">

		<ul class="nav nav-tabs" id="productCategoriesTab" role="tablist">
			<?php foreach ( $terms as $key => $term ) { ?>
				<?php 
				ob_start();
				smn_subcategory_icon( $term );
				$icon = ob_get_clean();
				if ( !$icon ) continue; 
				?>
				<li class="nav-item" role="presentation">
					<a class="nav-link <?php echo $key === 0 ? 'active' : ''; ?>" id="tab-<?php echo $term->term_id; ?>" data-bs-toggle="tab" href="#pane-<?php echo $term->term_id; ?>" role="tab" aria-controls="pane-<?php echo $term->term_id; ?>" aria-selected="<?php echo $key === 0 ? 'true' : 'false'; ?>">
						<div class="nav-tab-icon">
							<?php smn_subcategory_icon( $term ); ?>
						</div>
						<span class="nav-tab-title"><?php echo $term->name; ?></span>
					</a>
				</li>
			<?php } ?>
		</ul>

		<div class="tab-content" id="productCategoriesTabContent">
			<?php foreach ( $terms as $key => $term ) { ?>

				<?php 
				ob_start();
				smn_subcategory_icon( $term );
				$icon = ob_get_clean();
				if ( !$icon ) continue; 
				?>
				
				<?php
				$child_terms = get_terms( array(
					'taxonomy' => 'product_cat',
					'parent' => $term->term_id,
					'hide_empty' => true,
				) );
				?>
	
				<div class="tab-pane fade <?php echo $key === 0 ? 'show active' : ''; ?>" id="pane-<?php echo $term->term_id; ?>" role="tabpanel" aria-labelledby="tab-<?php echo $term->term_id; ?>">

					<div class="row g-1">

						<?php if( $child_terms ) {
							
							foreach ( $child_terms as $child_term ) { ?>
								<div class="col-md-6 col-lg-4">

									<div class="card stretch-linked-block">

										<div class="card-body">

											<a class="stretched-link card-title" href="<?php echo get_term_link( $child_term ); ?>" title="<?php echo $child_term->name; ?>"><?php echo $child_term->name; ?></a>

											<!-- <div class="row g-2 align-items-center">

												<div class="col-2">
													<?php smn_subcategory_image( $child_term ); ?>
												</div>
												<div class="col-10">
													<a class="stretched-link card-title" href="<?php echo get_term_link( $child_term ); ?>" title="<?php echo $child_term->name; ?>"><?php echo $child_term->name; ?></a>
												</div>

											</div> -->

										</div>

									</div>

								</div>
							<?php } 
						
						} ?>

						<div class="col-md-6 col-lg-4">
							<div class="card stretch-linked-block">
								<div class="card-body">

									<a class="stretched-link product-categories-tabs-view-all-link" href="<?php echo get_term_link( $term ); ?>" title="<?php echo $term->name; ?>">
										<?php _e( 'Ver todo', 'smn' ); ?>
									</a>

									<!-- <div class="row g-2 align-items-center">

										<div class="col-2">
											<?php smn_subcategory_image( $term ); ?>
										</div>
										<div class="col-10">
											<a class="stretched-link product-categories-tabs-view-all-link" href="<?php echo get_term_link( $term ); ?>" title="<?php echo $term->name; ?>">
												<?php _e( 'Ver todo', 'smn' ); ?>
											</a>
										</div>

									</div> -->

								</div>
							</div>
						</div>

					</div>

				</div>
			<?php } ?>
		</div>

	</section>

<?php } ?>

<section id="marcas" class="my-3 alignfull">
	<?php echo marcas_shortcode( array('exclude' => 847) ); ?>
</section>