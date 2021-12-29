<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/davidgoy/reflect
 * @since      1.0.0-beta.1
 *
 * @package    Wpreflect
 * @subpackage Wpreflect/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wpreflect
 * @subpackage Wpreflect/admin
 * @author Min Tat Goy <david@davidgoy.dev>
 */
class Wpreflect_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0-beta.1
	 * @access   private
	 * @var      string    $wpreflect    The ID of this plugin.
	 */
	private $wpreflect;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0-beta.1
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0-beta.1
	 * @param      string    $wpreflect       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $wpreflect, $version ) {

		$this->wpreflect = $wpreflect;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0-beta.1
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wpreflect_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wpreflect_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->wpreflect, plugin_dir_url( __FILE__ ) . 'css/wpreflect-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0-beta.1
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wpreflect_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wpreflect_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->wpreflect, plugin_dir_url( __FILE__ ) . 'js/wpreflect-admin.js', array( 'jquery' ), $this->version, false );

	}

}
