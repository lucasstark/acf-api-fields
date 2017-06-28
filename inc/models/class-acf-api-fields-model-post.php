<?php

class ACF_API_Fields_Model_Post {
	protected $api_endpoint_url;

	public $id = '';

	public $title = '';
	/**
	 * Data container
	 *
	 * @var array
	 */
	protected $data;

	/**
	 * Keys that have been changed since last update
	 *
	 * @var array
	 */
	protected $changed = array();

	/**
	 * Constructor
	 *
	 * @param array $data Data to initialise the object with
	 */
	public function __construct( $api_endpoint_url, $data = array() ) {
		$this->api  = $api_endpoint_url;
		$this->data = (array) $data;

		//Setup the fields so it can be used inside of the releationship field.
		$this->id = $this->get_id();
		$this->title = $this->get_title();
	}

	public function get_id() {
		if ( isset( $this->data['ID'] ) ) {
			return $this->data['ID'];
		} elseif ( isset( $this->data['id'] ) ) {
			return $this->data['id'];
		} elseif ( isset( $this->data['term_id'] ) ) {
			return $this->data['term_id'];
		} else {
			return false;
		}
	}

	public function get_title() {
		if ( isset( $this->data['title'] ) ) {
			if ( isset( $this->data['title']->rendered ) ) {
				return $this->data['title']->rendered;
			}
		} elseif ( isset( $this->data['name'] ) ) {
			return $this->data['name'];
		}

		return '';
	}


	/**
	 * Get a property
	 *
	 * See the specification for data keys/values returned by the API.
	 *
	 * @param string $key Key to retrieve
	 *
	 * @return mixed Post value for the key
	 */
	public function __get( $key ) {
		if ( ! isset( $this->data[ $key ] ) ) {
			return null;
		}

		return $this->data[ $key ];
	}

	/**
	 * Set a property
	 *
	 * @param string $key Key to replace
	 * @param mixed $value Value for the key
	 */
	public function __set( $key, $value ) {
		$this->data[ $key ]    = $value;
		$this->changed[ $key ] = true;
	}

	/**
	 * Get the raw internal post data
	 *
	 * Avoid use in favour of accessing via the properties instead.
	 *
	 * @return array Raw data from the API
	 */
	public function getRawData() {
		return $this->data;
	}


}
