<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://xwpstudio.com/
 * @since      1.7.0
 *
 * @package    hab_Hide_Admin_Bar_Based_On_User_Roles
 * @subpackage hab_Hide_Admin_Bar_Based_On_User_Roles/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.7.0
 * @package    hab_Hide_Admin_Bar_Based_On_User_Roles
 * @subpackage hab_Hide_Admin_Bar_Based_On_User_Roles/includes
 * @author     Ankit Panchal <wptoolsdev@gmail.com>
 */
class hab_Hide_Admin_Bar_Based_On_User_Roles {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.7.0
	 * @access   protected
	 * @var      hab_Hide_Admin_Bar_Based_On_User_Roles_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.7.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.7.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.7.0
	 */
	public function __construct() {
		if ( defined( 'HIDE_ADMIN_BAR_BASED_ON_USER_ROLES' ) ) {
			$this->version = HIDE_ADMIN_BAR_BASED_ON_USER_ROLES;
		} else {
			$this->version = '1.7.0';
		}
		$this->plugin_name = 'hide-admin-bar-based-on-user-roles';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - hab_Hide_Admin_Bar_Based_On_User_Roles_Loader. Orchestrates the hooks of the plugin.
	 * - hab_Hide_Admin_Bar_Based_On_User_Roles_i18n. Defines internationalization functionality.
	 * - hab_Hide_Admin_Bar_Based_On_User_Roles_Admin. Defines all hooks for the admin area.
	 * - hab_Hide_Admin_Bar_Based_On_User_Roles_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.7.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-hide-admin-bar-based-on-user-roles-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-hide-admin-bar-based-on-user-roles-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-hide-admin-bar-based-on-user-roles-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-hide-admin-bar-based-on-user-roles-public.php';

		$this->loader = new hab_Hide_Admin_Bar_Based_On_User_Roles_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the hab_Hide_Admin_Bar_Based_On_User_Roles_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.7.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new hab_Hide_Admin_Bar_Based_On_User_Roles_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.7.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new hab_Hide_Admin_Bar_Based_On_User_Roles_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'generate_admin_menu_page' );
		$this->loader->add_action( 'wp_ajax_save_user_roles', $plugin_admin, 'save_user_roles' );
		$this->loader->add_action( 'upgrader_process_complete', $plugin_admin, 'upgrader_process_complete' );
		$this->loader->add_action( 'wp_ajax_check_plugin_status', $plugin_admin, 'check_plugin_status' );
		$this->loader->add_action( 'wp_ajax_silent_install_plugin', $plugin_admin, 'handle_silent_install_plugin' );

		
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.7.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new hab_Hide_Admin_Bar_Based_On_User_Roles_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'wp', $plugin_public, 'hab_hide_admin_bar' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.7.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.7.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.7.0
	 * @return    hab_Hide_Admin_Bar_Based_On_User_Roles_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.7.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
