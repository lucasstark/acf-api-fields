<?php

class ACF_API_Fields_Field_Relationship extends acf_field {

	private static $instance;

	public static function register() {
		if ( self::$instance == null ) {
			self::$instance = new ACF_API_Fields_Field_Relationship();
		}
	}

	public static $_endpoints = array();

	public function __construct() {
		$this->name = 'api_relationship';
		$this->label = __( 'API Relationship', 'acf-wp-rest-api-fields' );
		$this->category = 'API';

		$this->defaults = array(
		    'api_endpoints' => '',
		    'api_endpoint_type' => 'post',//Not currently used
		    'api_title_field' => 'title',//Not currently used
		    'api_id_field' => 'id',//Not current used
		    'return_format' => 'object',
		    'taxonomy' => array(),
		    'min' => '',
		    'max' => '',
		    'filters' => array('search', 'endpoints'),
		    'elements' => array(),
		    'multiple' => true,
		);

		parent::__construct();
	}

	public function input_admin_enqueue_scripts() {
		wp_enqueue_script( 'acf-field-api-relationship', ACF_API_Fields()->plugin_url() . '/js/field-api-relationship.js', array('jquery', 'acf-input'), ACF_API_Fields()->assets_version() );
	}

	public function input_admin_l10n( $l10n ) {
		parent::input_admin_l10n( $l10n );
	}

	public function load_field( $field ) {
		if ( empty( $this->l10n ) ) {
			$this->l10n['fields'] = array();
		}

		$field['api_endpoints'] = acf_get_array( $field['api_endpoints'] );

		$this->l10n['fields'][$field['key']] = $field['api_endpoints'];
		return $field;
	}

	public function render_field_settings( $field ) {

		$field['api_endpoints'] = acf_encode_choices( $field['api_endpoints'] );

		// choices
		acf_render_field_setting( $field, array(
		    'label' => __( 'Endpoints', 'acf' ),
		    'instructions' => __( 'Enter each endpoint on a new line.', 'acf-wp-rest-api-fields' ) . '<br /><br />' . __( 'Specify both a name and url like this:', 'acf-wp-rest-api-fields' ) . '<br /><br />' . __( 'Name : URL', 'acf-wp-rest-api-fields' ),
		    'type' => 'textarea',
		    'name' => 'api_endpoints',
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
		    'data-endpoint' => '',
		);


		$field['filters'] = array();
		$field['filters'][] = 'search';
		if ( !empty( $field['api_endpoints'] ) && count( $field['api_endpoints'] ) > 1 ) {
			$field['filters'][] = 'endpoints';
		}
		
		$atts['data-endpoint'] = array_values( $field['api_endpoints'] )[0];

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

	public function update_field( $field ) {
		$field['api_endpoints'] = acf_decode_choices( $field['api_endpoints'] );
		return $field;
	}

}

ACF_API_Fields_Field_Relationship::register();
