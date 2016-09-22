<?php

class ACF_API_Fields_API {

	/**
	 *
	 * @param array $field The ACF Field
	 * @param array $args Query args to pass to the API
	 *
	 * @return \ACF_API_Fields_Model_Post[]
	 */
	public function get_posts( $field, $args ) {

		$api_endpoint_url = add_query_arg( $args, $field['api_endpoint_url'] );
		$response         = wp_remote_get( $api_endpoint_url, array( 'timeout' => 20 ) );

		if ( is_wp_error( $response ) ) {
			//$this->debug( sprintf( __( 'HTTP request returned an error: %s (%s).', 'json-shortcode' ), $response->get_error_message(), $response->get_error_code() ) );
			echo $response->get_error_message();

			return;
		}

		if ( $response['response']['code'] != '200' ) {
			wp_die( sprintf( __( 'Server responded with: %s (%d). Data may not be usable.', 'acf-wp-rest-api-fields' ), $response['response']['message'], $response['response']['code'] ) );
		}

		$json_response = wp_remote_retrieve_body( $response );

		if ( empty( $json_response ) ) {
			return array();
		}


		$results = array();
		foreach ( $args['include'] as $id ) {
			$results[ $id ] = null;
		}

		$data = json_decode( $json_response );

		foreach ( $data as $post_data ) {

			$model = apply_filters( 'acf_api_fields_get_api_model', false, $post_data, $field, $args );
			if ( ! empty( $model ) ) {
				$results[$post_data->id] = $model;
			} else {
				$results[$post_data->id] = new ACF_API_Fields_Model_Post( $api_endpoint_url, $post_data );
			}
		}

		return $results;
	}

}
