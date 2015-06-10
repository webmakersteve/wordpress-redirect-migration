<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://webmakersteve.com
 * @since             1.0.0
 * @package           redirect_migration
 *
 * @wordpress-plugin
 * Plugin Name:       Redirect Mapper
 * Plugin URI:        http://webmakersteve.com/development/wordpress/redirect-mapper
 * Description:       Plugin for WordPress to map URLs to new areas of the site with an optional fallback.
 * Version:           1.0.0
 * Author:            Stephen Parente
 * Author URI:        http://webmakersteve.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       redirect-migration
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-redirect-migration-activator.php
 */
function activate_redirect_migration() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-redirect-migration-activator.php';
	redirect_migration_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-redirect-migration-deactivator.php
 */
function deactivate_redirect_migration() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-redirect-migration-deactivator.php';
	redirect_migration_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_redirect_migration' );
register_deactivation_hook( __FILE__, 'deactivate_redirect_migration' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-redirect-migration.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_redirect_migration() {

	$plugin = new Redirect_Migration();
	$plugin->run();

}
run_redirect_migration();
