<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    redirect_migration
 * @subpackage redirect_migration/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    redirect_migration
 * @subpackage redirect_migration/admin
 * @author     Your Name <email@example.com>
 */
class Redirect_Migration_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $redirect_migration    The ID of this plugin.
	 */
	private $redirect_migration;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $redirect_migration       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $redirect_migration, $version ) {

		$this->redirect_migration = $redirect_migration;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in redirect_migration_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The redirect_migration_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->redirect_migration, plugin_dir_url( __FILE__ ) . 'css/redirect-migration-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in redirect_migration_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The redirect_migration_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->redirect_migration, plugin_dir_url( __FILE__ ) . 'js/redirect-migration-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function add_plugin_page() {
		add_menu_page(
			'Redirect Map',
			'Redirects',
			'manage_options',
			'redirect_map',
			array( $this, 'create_admin_page' ),
			'dashicons-admin-site',
			81
		);
	}

	public function create_admin_page() {
		$maps = Redirect_Migration_Map::all();
		
		include('partials/redirect-migration-admin-display.php');
	}

	public function plugin_page_init() {

	}

}
