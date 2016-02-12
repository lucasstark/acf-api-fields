<?php
// vars
$values = array();


// Lang
if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
	$atts['data-lang'] = ICL_LANGUAGE_CODE;
}



// width for select filters
$width = array(
    'search' => 0,
    'endpoints' => 0,
);

if ( !empty( $field['filters'] ) ) {

	$width = array(
	    'search' => 50,
	    'endpoints' => 50,
	);

	foreach ( array_keys( $width ) as $k ) {
		if ( !in_array( $k, $field['filters'] ) ) {
			$width[$k] = 0;
		}
	}

	// search
	if ( $width['endpoints'] == 0 ) {
		$width['search'] = ( $width['search'] == 0 ) ? 0 : 100;
	}
}


// post type filter
$endpoints = array();

if ( $width['endpoints'] ) {
	if ( !empty( $field['api_endpoints'] ) ) {
		foreach ( $field['api_endpoints'] as $name => $endpoint ) {
			$endpoints[$endpoint] = $name;
		}
	}
}
?>
<div <?php acf_esc_attr_e( $atts ); ?>>

	<div class="acf-hidden">
		<input type="hidden" name="<?php echo $field['name']; ?>" value="" />
	</div>

	<?php if ( $width['search'] || $width['endpoints'] ): ?>
		<div class="filters">
			<ul class="acf-hl">
				<?php if ( $width['search'] ): ?>
					<li style="width:<?php echo $width['search']; ?>%;">
						<div class="inner">
							<input class="filter" data-filter="s" placeholder="<?php _e( "Search...", 'acf' ); ?>" type="text" />
						</div>
					</li>
				<?php endif; ?>

				<?php if ( $width['endpoints'] ): ?>
					<li style="width:<?php echo $width['endpoints']; ?>%;">
						<div class="inner">
							<select class="filter" data-filter="endpoint">
								<?php foreach ( $endpoints as $k => $v ): ?>
									<option value="<?php echo $k; ?>"><?php echo $v; ?></option>
								<?php endforeach; ?>
							</select>
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
					$remote_posts = ACF_API_Fields()->api->get_posts( $field, array(
					    'include' => $field['value']
						) );

					// set choices
					if ( !empty( $remote_posts ) ):
						foreach ( $remote_posts as $remote_post ):
							?><li>
								<input type="hidden" name="<?php echo $field['name']; ?>[]" value="<?php echo esc_attr( $remote_post->id ); ?>" />
								<span data-id="<?php echo esc_attr( $remote_post->id ); ?>" class="acf-rel-item">
									<?php echo esc_html( $remote_post->title->rendered ); ?>
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

