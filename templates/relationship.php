<?php
// vars
$values = array();



// Lang
if ( defined( 'ICL_LANGUAGE_CODE' ) ) {

	$atts['data-lang'] = ICL_LANGUAGE_CODE;
}


// data types
$field['post_type'] = acf_get_array( $field['post_type'] );
$field['taxonomy'] = acf_get_array( $field['taxonomy'] );


// width for select filters
$width = array(
    'search' => 0,
    'post_type' => 0,
    'taxonomy' => 0
);

if ( !empty( $field['filters'] ) ) {

	$width = array(
	    'search' => 50,
	    'post_type' => 25,
	    'taxonomy' => 25
	);

	foreach ( array_keys( $width ) as $k ) {
		if ( !in_array( $k, $field['filters'] ) ) {
			$width[$k] = 0;
		}
	}

	// search
	if ( $width['search'] == 0 ) {
		$width['post_type'] = ( $width['post_type'] == 0 ) ? 0 : 50;
		$width['taxonomy'] = ( $width['taxonomy'] == 0 ) ? 0 : 50;
	}

	// post_type
	if ( $width['post_type'] == 0 ) {
		$width['taxonomy'] = ( $width['taxonomy'] == 0 ) ? 0 : 50;
	}

	// taxonomy
	if ( $width['taxonomy'] == 0 ) {
		$width['post_type'] = ( $width['post_type'] == 0 ) ? 0 : 50;
	}

	// search
	if ( $width['post_type'] == 0 && $width['taxonomy'] == 0 ) {
		$width['search'] = ( $width['search'] == 0 ) ? 0 : 100;
	}
}


// post type filter
$post_types = array();

if ( $width['post_type'] ) {
	if ( !empty( $field['post_type'] ) ) {
		$post_types = $field['post_type'];
	} else {
		$post_types = acf_get_post_types();
	}
	$post_types = acf_get_pretty_post_types( $post_types );
}


// taxonomy filter
$taxonomies = array();
$term_groups = array();

if ( $width['taxonomy'] ) {

	// taxonomies
	if ( !empty( $field['taxonomy'] ) ) {

		// get the field's terms
		$term_groups = acf_get_array( $field['taxonomy'] );
		$term_groups = acf_decode_taxonomy_terms( $term_groups );


		// update taxonomies
		$taxonomies = array_keys( $term_groups );
	} elseif ( !empty( $field['post_type'] ) ) {

		// loop over post types and find connected taxonomies
		foreach ( $field['post_type'] as $post_type ) {

			$post_taxonomies = get_object_taxonomies( $post_type );

			// bail early if no taxonomies
			if ( empty( $post_taxonomies ) ) {

				continue;
			}

			foreach ( $post_taxonomies as $post_taxonomy ) {

				if ( !in_array( $post_taxonomy, $taxonomies ) ) {

					$taxonomies[] = $post_taxonomy;
				}
			}
		}
	} else {

		$taxonomies = acf_get_taxonomies();
	}


	// terms
	$term_groups = acf_get_taxonomy_terms( $taxonomies );


	// update $term_groups with specific terms
	if ( !empty( $field['taxonomy'] ) ) {
		foreach ( array_keys( $term_groups ) as $taxonomy ) {
			foreach ( array_keys( $term_groups[$taxonomy] ) as $term ) {
				if ( !in_array( $term, $field['taxonomy'] ) ) {
					unset( $term_groups[$taxonomy][$term] );
				}
			}
		}
	}
}
// end taxonomy filter
?>
<div <?php acf_esc_attr_e( $atts ); ?>>

	<div class="acf-hidden">
		<input type="hidden" name="<?php echo $field['name']; ?>" value="" />
	</div>

	<?php if ( $width['search'] || $width['post_type'] || $width['taxonomy'] ): ?>
		<div class="filters">
			<ul class="acf-hl">
				<?php if ( $width['search'] ): ?>
					<li style="width:<?php echo $width['search']; ?>%;">
						<div class="inner">
							<input class="filter" data-filter="s" placeholder="<?php _e( "Search...", 'acf' ); ?>" type="text" />
						</div>
					</li>
				<?php endif; ?>
			</ul>
		</div>
	<?php endif; ?>

	<div class="selection acf-cf">
		<div class="choices">
			<ul class="acf-bl list"></ul>
		</div>

		<div class="values">
			<ul class="acf-bl list">
				<?php
				if ( !empty( $field['value'] ) ):
					// get posts
					$remote_posts = ACF_API_Fields()->api->get_posts($field, array(
					    'include' => $field['value']
					) );
					
					// set choices
					if ( !empty( $remote_posts ) ):
						foreach ( $remote_posts as $remote_post ):
							?><li>
								<input type="hidden" name="<?php echo $field['name']; ?>[]" value="<?php echo esc_attr($remote_post->id); ?>" />
								<span data-id="<?php echo esc_attr($remote_post->id); ?>" class="acf-rel-item">
									<?php echo esc_html( $remote_post->title ); ?>
									<a href="#" class="acf-icon -minus small dark" data-name="remove_item"></a>
								</span>
							</li><?php
						endforeach;

					endif;

				endif;
				?>
			</ul>
		</div>
	</div>
</div>

