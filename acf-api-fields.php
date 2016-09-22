<?php

/*
  Plugin Name: Advanced Custom Fields: API Fields
  Plugin URI: https://github.com/lucasstark/acf-api-fields
  Description: WP Rest API Object and Relationship Fields
  Version: 0.0.2
  Author: Lucas Stark
  Author URI: http://www.github.com/lucasstark/
  License: GPLv2 or later
  License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */



load_plugin_textdomain( 'acf-wp-rest-api-fields', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );

class ACF_API_Fields_Main {

	private static $instance;

	public static function register() {
		if ( self::$instance == null ) {
			self::$instance = new ACF_API_Fields_Main();
		}
	}

	

	/**
	 * 
	 * @return ACF_API_Fields_Main
	 */
	public static function instance() {
		self::register();
		return self::$instance;
	}
	
	
	/**
	 * Version string for JS and CSS. 
	 * @var string 
	 */
	private $_assets_version = '1.0.0';
	
	/**
	 *
	 * @var ACF_API_Fields_API 
	 */
	public $api;
	
	
	private function __construct() {
		add_action('plugins_loaded', array($this, 'on_plugins_loaded'));
		add_action( 'acf/include_field_types', array($this, 'include_field_types') );
	}
	
	public function on_plugins_loaded() {
		
		//I know these names are really long, I'll refactor later. 
		require_once 'inc/models/class-acf-api-fields-model-post.php';
		require_once 'inc/class-acf-api-fields-api.php';
		
		$this->api = new ACF_API_Fields_API();
	}
	

	public function include_field_types( $version ) {
		//require_once('inc/acf-wp-rest-api-fields-object.php');
		require_once('inc/fields/class-acf-api-fields-field-relationship.php');
	}

	/**
	 * Gets the static asset version or a random string if SCRIPT_DEBUG is enabled. 
	 * @return string
	 */
	public function assets_version() {
		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
			//return time();
			return $this->_assets_version;
		} else {
			return $this->_assets_version;
		}
	}

	public function plugin_url() {
		return plugin_dir_url( __FILE__ );
	}

	/**
	 * Get the plugin path.
	 * @access public
	 * @return string
	 */
	public function plugin_dir() {
		return untrailingslashit( plugin_dir_path( __FILE__ ) );
	}

}

ACF_API_Fields_Main::register();

/**
 * 
 * @return ACF_API_Fields_Main
 */
function ACF_API_Fields() {
	return ACF_API_Fields_Main::instance();
}
