<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link               https://thangdangblog.com/
 * @since             1.0.0
 * @package           Mshopkeeper_Api
 *
 * @wordpress-plugin
 * Plugin Name:       Mshopkeeper Api
 * Plugin URI:         https://thangdangblog.com/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Đặng Quốc Thắng
 * Author URI:         https://thangdangblog.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       mshopkeeper-api
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
define( 'MSHOPKEEPER_API_VERSION', '1.0.0' );

/**
 * Define default variable
 */
define( 'MSHOPKEEPER_API_PATH_PLUGIN', plugin_dir_path( __FILE__ ) );
define( 'MSHOPKEEPER_API_PATH_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

define( 'MSHOPKEEPER_API_SCHEMA', 'https://' );
define( 'MSHOPKEEPER_API_URL_AUTH', 'graphapi.mshopkeeper.vn/auth' );
define( 'MSHOPKEEPER_API_URL', 'graphapi.mshopkeeper.vn' );
define( 'MSHOPKEEPER_BASENAME', plugin_basename(__FILE__) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-mshopkeeper-api-activator.php
 */
function activate_mshopkeeper_api() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-mshopkeeper-api-activator.php';
	Mshopkeeper_Api_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-mshopkeeper-api-deactivator.php
 */
function deactivate_mshopkeeper_api() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-mshopkeeper-api-deactivator.php';

	Mshopkeeper_Api_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_mshopkeeper_api' );
register_deactivation_hook( __FILE__, 'deactivate_mshopkeeper_api' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-mshopkeeper-api.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_mshopkeeper_api() {

	$plugin = new Mshopkeeper_Api();
	$plugin->run();

}
run_mshopkeeper_api();
