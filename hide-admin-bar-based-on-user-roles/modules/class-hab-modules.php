<?php
/**
 * Optional modules, added in 7.4.0.
 *
 * Dashboard access, the admin bar inside the dashboard, login redirects and strict mode (7.4.0);
 * switch to user, and hiding admin menu items, admin bar items, admin notices and dashboard
 * widgets by role (7.5.0). Each module is off until it's switched on and keeps its settings in
 * the hab_modules option. None of them changes the original admin bar rules or the hab_settings
 * option.
 *
 * Defining HAB_DISABLE_MODULES as true in wp-config.php switches every module off and keeps
 * their settings.
 *
 * @link       https://wpankit.com/
 * @since      7.4.0
 *
 * @package    hab_Hide_Admin_Bar_Based_On_User_Roles
 * @subpackage hab_Hide_Admin_Bar_Based_On_User_Roles/modules
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/class-hab-modules-settings.php';
require_once __DIR__ . '/class-hab-modules-admin.php';
require_once __DIR__ . '/class-hab-dashboard-access.php';
require_once __DIR__ . '/class-hab-dashboard-admin-bar.php';
require_once __DIR__ . '/class-hab-login-redirect.php';
require_once __DIR__ . '/class-hab-strict-mode.php';
require_once __DIR__ . '/class-hab-admin-items.php';
require_once __DIR__ . '/class-hab-switch-user.php';
require_once __DIR__ . '/class-hab-admin-menu.php';
require_once __DIR__ . '/class-hab-admin-bar-items.php';
require_once __DIR__ . '/class-hab-admin-notices.php';
require_once __DIR__ . '/class-hab-dashboard-widgets.php';

/**
 * Start the settings pages and every module that is switched on.
 *
 * @since 7.4.0
 */
function hab_run_modules() {
	if ( is_admin() ) {
		new hab_Modules_Admin();
	}

	if ( defined( 'HAB_DISABLE_MODULES' ) && HAB_DISABLE_MODULES ) {
		return;
	}

	// Store the defaults once. The option is then autoloaded with the other options, so reading it
	// doesn't add a database query to every page load.
	if ( null === get_option( hab_Modules_Settings::OPTION, null ) ) {
		add_option( hab_Modules_Settings::OPTION, hab_Modules_Settings::defaults(), '', true );
	}

	$settings = hab_Modules_Settings::get();

	if ( $settings['dashboard_access']['enabled'] && $settings['dashboard_access']['roles'] ) {
		new hab_Dashboard_Access( $settings['dashboard_access'] );
	}

	if ( $settings['dashboard_admin_bar']['enabled'] && $settings['dashboard_admin_bar']['roles'] ) {
		new hab_Dashboard_Admin_Bar( $settings['dashboard_admin_bar'] );
	}

	if ( $settings['login_redirect']['enabled'] && $settings['login_redirect']['urls'] ) {
		new hab_Login_Redirect( $settings['login_redirect'] );
	}

	if ( $settings['strict_mode']['enabled'] ) {
		new hab_Strict_Mode();
	}

	if ( $settings['switch_user']['enabled'] ) {
		new hab_Switch_User();
	}

	if ( $settings['admin_menu']['enabled'] && $settings['admin_menu']['hidden'] ) {
		new hab_Admin_Menu( $settings['admin_menu'] );
	}

	if ( $settings['admin_bar_items']['enabled'] && $settings['admin_bar_items']['hidden'] ) {
		new hab_Admin_Bar_Items( $settings['admin_bar_items'] );
	}

	if ( $settings['admin_notices']['enabled'] && $settings['admin_notices']['roles'] ) {
		new hab_Admin_Notices( $settings['admin_notices'] );
	}

	if ( $settings['dashboard_widgets']['enabled'] && $settings['dashboard_widgets']['hidden'] ) {
		new hab_Dashboard_Widgets( $settings['dashboard_widgets'] );
	}
}

add_action( 'plugins_loaded', 'hab_run_modules' );

/**
 * Delete the module settings when the plugin is uninstalled.
 *
 * @since 7.4.0
 */
function hab_modules_uninstall_cleanup() {
	delete_option( hab_Modules_Settings::OPTION );
	delete_option( hab_Admin_Items::WIDGETS_OPTION );
}

habbourp_fs()->add_action( 'after_uninstall', 'hab_modules_uninstall_cleanup' );
