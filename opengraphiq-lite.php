<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://bold-themes.com
 * @since             1.0.0
 * @package           Opengraphiq-lite
 *
 * @wordpress-plugin
 * Plugin Name:       OpenGraphiq lite
 * Plugin URI:        https://opengraphiq.bold-themes.com
 * Description:       Enables you to create Open Graph images for your pages and posts - Lite version
 * Version:           1.0.0
 * Author:            BoldThemes 
 * Author URI:        https://bold-themes.com/
 * Text Domain:       opengraphiq-lite
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

define( 'OPENGRAPHIQ_LITE_VERSION', '1.0.0' );

!defined('OPENGRAPHIQ_PATH') && define('OPENGRAPHIQ_PATH', plugin_dir_path( __FILE__ )); 

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-opengraphiq.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function opengraphiq_run() {

	$plugin = new Opengraphiq();
	$plugin->run();

}
opengraphiq_run();
