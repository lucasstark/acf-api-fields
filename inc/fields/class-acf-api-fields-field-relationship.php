<?php

class ACF_API_Fields_Field_Relationship extends acf_field {

	private static $instance;

	public static function register() {
		if ( self::$instance == null ) {
			self::$instance = new ACF_API_Fields_Field_Relationship();
		}
	}

	public function __construct() {
		$this->name = 'api_relationship';
		$this->label = __( 'API Relationship', 'acf-wp-rest-api-fields' );
		$this->category = 'API';

		$this->defaults = array(
		    'api_endpoint_url' => '',
		    'api_endpoint_type' => 'post',
		    'api_title_field' => 'title',
		    'api_id_field' => 'id',
		    'return_format' => 'object',
		    'post_type' => array(),
		    'taxonomy' => array(),
		    'min' => '',
		    'max' => '',
		    'filters' => array('search'),
		    'elements' => array(),
		    'multiple' => true,
		);

		parent::__construct();
	}

	public function input_admin_enqueue_scripts() {
		wp_enqueue_script( 'acf-field-api-relationship', ACF_API_Fields()->plugin_url() . '/js/field-api-relationship.js', array('jquery', 'acf-input'), ACF_API_Fields()->assets_version() );
	}

	function render_field_settings( $field ) {

		acf_render_field_setting( $field, array(
		    'label' => __( 'End Point URL', 'acf-wp-rest-api-fields' ),
		    'instructions' => __( 'The API Endpoint URL', 'acf-wp-rest-api-fields' ),
		    'type' => 'text',
		    'name' => 'api_endpoint_url',
		    'append' => '',
		) );

		acf_render_field_setting( $field, array(
			'label' => __( 'End Point Type', 'acf-wp-rest-api-fields' ),
			'instructions' => __( 'The API Endpoint Type', 'acf-wp-rest-api-fields' ),
			'type' => 'select',
			'choices' => array(
				'post' => 'Post Type',
				'taxonomy' => 'Taxonomy'
			),
			'name' => 'api_endpoint_type',
		) );

		acf_render_field_setting( $field, array(
			'label' => __( 'End Point ID Field', 'acf-wp-rest-api-fields' ),
			'instructions' => __( 'The API Endpoint ID Field', 'acf-wp-rest-api-fields' ),
			'type' => 'text',
			'name' => 'api_id_field',
		) );

		acf_render_field_setting( $field, array(
			'label' => __( 'End Point Title Field', 'acf-wp-rest-api-fields' ),
			'instructions' => __( 'The API Endpoint Title Field', 'acf-wp-rest-api-fields' ),
			'type' => 'text',
			'name' => 'api_title_field',
		) );
	}

	public function render_field( $field ) {
		
		$atts = array(
		    'id' => $field['id'],
		    'class' => "acf-relationship {$field['class']}",
		    'data-min' => $field['min'],
		    'data-max' => $field['max'],
		    'data-s' => '',
		    'data-post_type' => '',
		    'data-taxonomy' => '',
		    'data-paged' => 1,
		    'data-api_endpoint' => $field['api_endpoint_url'],
			'data-api_endpoint_type' => $field['api_endpoint_type'],
		    'data-api_endpoint_id_field' => $field['api_id_field'],
			'data-api_endpoint_title_field' => $field['api_title_field']
		);

		include ACF_API_Fields()->plugin_dir() . '/templates/relationship.php';
	}

	public function format_value( $value, $post_id, $field ) {

		// bail early if no value
		if ( empty( $value ) ) {
			return $value;
		}


		// force value to array
		$value = acf_get_array( $value );

		// convert values to int
		$value = array_map( 'intval', $value );

		// load posts if needed
		if ( $field['return_format'] == 'object' ) {
			$value = ACF_API_Fields()->api->get_posts( $field, array(
			    'include' => $value
			) );
		}
		
		// convert back from array if neccessary
		if ( !$field['multiple'] ) {
			$value = array_shift( $value );
		}

		// return value
		return $value;
	}

}

ACF_API_Fields_Field_Relationship::register();
