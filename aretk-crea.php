<?php
/**
 * @link              https://aretk.com/
 * @since             1.0.0
 * @package           Aretk_Crea
 *
 * @wordpress-plugin
 * Plugin Name:       ARETK CREA
 * Plugin URI:        https://aretk.com/
 * Description:       Display your Real Estate Listings and capture and manage all your leads with a fully integrated Customer Management System (CMS).  Integrate CREA DDF listings with an optional add-on.
 * Version:           1.20.10.29.01
 * Author:            ARETK Inc.
 * Author URI:        https://www.aretk.com
 * License:           GPL-2.0+
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       aretk-crea
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! defined( 'ARETK_CREA_PLUGIN_URL' ) ) {
	define( 'ARETK_CREA_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'ARETK_CREA_PLUGIN_PATH' ) ) {
	define( 'ARETK_CREA_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'ARETK_CREA_PLUGIN_BASENAME' ) ) {
	define( 'ARETK_CREA_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-aretk-crea-activator.php
 */
function aretkcrea_activate_aretk_crea() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-aretk-crea-activator.php';
	Aretk_Crea_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-aretk-crea-deactivator.php
 */
function aretkcrea_deactivate_aretk_crea() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-aretk-crea-deactivator.php';
	Aretk_Crea_Deactivator::aretkcrea_deactivate();
}

register_activation_hook( __FILE__, 'aretkcrea_activate_aretk_crea' );
register_deactivation_hook( __FILE__, 'aretkcrea_deactivate_aretk_crea' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-aretk-crea.php';

/**
 * The class responisble for defineing constant values.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/constant.php';

/**
 * Class used to save data into microsoft excel format.
 */
if ( ! class_exists( 'ExcelWriter' ) ) {
	require_once plugin_dir_path( __FILE__ ) . 'includes/excelwriter.inc.php';
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function aretkcrea_run_aretk_crea() {
	$plugin = new Aretk_Crea();
	$plugin->run();
}

aretkcrea_run_aretk_crea();