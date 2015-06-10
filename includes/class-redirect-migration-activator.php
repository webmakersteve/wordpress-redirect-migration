<?php

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    redirect_migration
 * @subpackage redirect_migration/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    redirect_migration
 * @subpackage redirect_migration/includes
 * @author     Your Name <email@example.com>
 */
class Redirect_Migration_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;

		$installed_ver = get_option( "redirect_map_db_version" );

		$table_name = $wpdb->prefix . "redirect_map";

		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
		  id mediumint(9) NOT NULL AUTO_INCREMENT,
		  time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			status integer(3) DEFAULT 301 NOT NULL,
		  url_from varchar(55) DEFAULT '' NOT NULL,
			url_to varchar(55) DEFAULT '' NOT NULL,
			active boolean DEFAULT 1 NOT NULL,
		  UNIQUE KEY id (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

		add_option( "redirect_map_db_version", "1.0" );
	}

}
