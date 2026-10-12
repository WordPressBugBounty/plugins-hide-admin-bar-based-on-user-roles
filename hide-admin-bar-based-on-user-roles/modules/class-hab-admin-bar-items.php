<?php
/**
 * Admin bar items module.
 *
 * @link       https://wpankit.com/
 * @since      7.5.0
 *
 * @package    hab_Hide_Admin_Bar_Based_On_User_Roles
 * @subpackage hab_Hide_Admin_Bar_Based_On_User_Roles/modules
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Removes the selected items from the admin bar for the selected roles, on the front end and in
 * the dashboard.
 *
 * @since 7.5.0
 */
class hab_Admin_Bar_Items {

	/**
	 * Module settings.
	 *
	 * @var array
	 */
	private $settings;

	/**
	 * Register the hook.
	 *
	 * @since 7.5.0
	 * @param array $settings Module settings from hab_Modules_Settings.
	 */
	public function __construct( $settings ) {
		$this->settings = $settings;

		add_action( 'admin_bar_menu', array( $this, 'remove_items' ), PHP_INT_MAX );
	}

	/**
	 * Remove the hidden items for the current user.
	 *
	 * @since 7.5.0
	 * @param WP_Admin_Bar $wp_admin_bar The admin bar.
	 */
	public function remove_items( $wp_admin_bar ) {
		$hidden = hab_Modules_Settings::hidden_for_roles( $this->settings['hidden'], wp_get_current_user()->roles );

		foreach ( $hidden as $id ) {
			// The button that opens the admin menu on phones always stays.
			if ( 'menu-toggle' !== $id ) {
				$wp_admin_bar->remove_node( $id );
			}
		}
	}
}
