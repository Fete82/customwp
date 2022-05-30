<?php
/**
 * Plugin Name: WPGetAPI
 * Plugin URI:  https://wordpress.org/plugins/wpgetapi/
 * Description: Connect to external API's and display the API data.
 * Author: WPGetAPI
 * Author URI:  https://wpgetapi.com/
 * Version: 1.4.7
 * Text Domain: wpgetapi
 * Domain Path: /languages/
 * License: GPL2 or later
 * 
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Main Class.
 *
 * @since 1.0.0
 */
final class WpGetApi {

	/**
	 * @var The one true instance
	 * @since 1.0.0
	 */
	protected static $_instance = null;

	public $version = '1.4.7';

	/**
	 * Main Instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Throw error on object clone.
	 *
	 * @since 1.0.0
	 * @access protected
	 * @return void
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'wpgetapi' ), '1.0.0' );
	}

	/**
	 * Disable unserializing of the class.
	 * @since 1.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'wpgetapi' ), '1.0.0' );
	}

	/**
	 * 
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->define_constants();
		$this->hooks();
		$this->includes();
		
		do_action( 'wpgetapi_loaded' );
	}

	/**
	 * Define Constants.
	 * @since  1.0.0
	 */
	private function define_constants() {
		$this->define( 'WPGETAPIDIR',plugin_dir_path( __FILE__ ) );
		$this->define( 'WPGETAPIURL',plugin_dir_url( __FILE__ ) );
		$this->define( 'WPGETAPIBASENAME', plugin_basename( __FILE__ ) );
		$this->define( 'WPGETAPIVERSION', $this->version );
	}

	/**
	 * Define hooks.
	 * @since  1.4.2
	 */
	private function hooks() {

		$plugin_file = WPGETAPIBASENAME;
		add_filter( "plugin_action_links_{$plugin_file}", array( $this, 'plugin_action_links' ), 10, 4 );
		add_filter( 'plugin_row_meta', array( $this, 'filter_plugin_row_meta' ), 10, 4 );
		
	}

	/**
	 * Define constant if not already set.
	 * @since  1.0.0
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}


	/**
	 * Include required files.
	 * @since  1.0.0
	 */
	public function includes() {
		
		require_once ( WPGETAPIDIR . '/lib/cmb2/init.php' );

		include_once ( WPGETAPIDIR . 'includes/class-encryption.php' );
		include_once ( WPGETAPIDIR . 'includes/class-admin-options.php' );
		
		include_once ( WPGETAPIDIR . 'includes/functions.php' );
		include_once ( WPGETAPIDIR . 'includes/class-enqueues.php' );
		include_once ( WPGETAPIDIR . 'includes/class-api.php' );

		include_once ( WPGETAPIDIR . 'frontend/functions.php' );
		
	}

	/**
	 * Filters the array of row meta for each plugin in the Plugins list table.
	 *
	 * @param array<int,string> $plugin_meta An array of the plugin row's meta data.
	 * @param string            $plugin_file Path to the plugin file relative to the plugins directory.
	 * @return array<int,string> An array of the plugin row's meta data.
	 */
	function filter_plugin_row_meta( array $plugin_meta, $plugin_file ) {
		if ( 'wpgetapi/wpgetapi.php' !== $plugin_file ) {
			return $plugin_meta;
		}

		$plugin_meta[] = sprintf(
			'<a href="%1$s">%2$s</a>',
			'https://wpgetapi.com/docs/?utm_campaign=Docs&utm_medium=plugin&utm_source=external',
			esc_html( 'Docs', 'wpgetapi' )
		);

		return $plugin_meta;
	}


	/**
	 * Adds items to the plugin's action links on the Plugins listing screen.
	 *
	 * @param array<string,string> $actions     Array of action links.
	 * @param string               $plugin_file Path to the plugin file relative to the plugins directory.
	 * @param mixed[]              $plugin_data An array of plugin data.
	 * @param string               $context     The plugin context.
	 * @return array<string,string> Array of action links.
	 */
	function plugin_action_links( $actions, $plugin_file, $plugin_data, $context ) {
		$new = array(
			'setup'    => sprintf(
				'<a href="%s">%s</a>',
				esc_url( admin_url( 'admin.php?page=wpgetapi_setup' ) ),
				esc_html__( 'API Setup', 'wpgetapi' )
			),
		);

		return array_merge( $new, $actions );
	}


}


/**
 * Run the plugin.
 */
function WpGetApi() {
	return WpGetApi::instance();
}
WpGetApi();