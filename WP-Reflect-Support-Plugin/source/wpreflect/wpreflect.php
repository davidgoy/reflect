<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/davidgoy/reflect
 * @since             1.0.0-beta.1
 * @package           Wpreflect
 *
 * @wordpress-plugin
 * Plugin Name:       WP Reflect Support
 * Plugin URI:        https://github.com/davidgoy/reflect/
 * Description:       Enables your WordPress site to work with Reflect.
 * Version:           1.0.0-beta.2
 * Author:            Min Tat Goy
 * Author URI:        https://davidgoy.dev/
 * License:           GPL-2.0+
 * License URI:       https://www.gnu.org/licenses/gpl.html
 * Text Domain:       wpreflect
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WPREFLECT_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wpreflect-activator.php
 */
function activate_wpreflect() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpreflect-activator.php';
	Wpreflect_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wpreflect-deactivator.php
 */
function deactivate_wpreflect() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpreflect-deactivator.php';
	Wpreflect_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wpreflect' );
register_deactivation_hook( __FILE__, 'deactivate_wpreflect' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wpreflect.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0-beta.1
 */
function run_wpreflect() {

	$plugin = new Wpreflect();
	$plugin->run();

}
run_wpreflect();
