<?php
/**
 * Dashboard widgets module.
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
 * Removes the selected Dashboard widgets, and the Welcome panel, for the selected roles.
 *
 * @since 7.5.0
 */
class hab_Dashboard_Widgets {

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

		add_action( 'wp_dashboard_setup', array( $this, 'remove_widgets' ), PHP_INT_MAX );
	}

	/**
	 * Remove the hidden widgets for the current user.
	 *
	 * @since 7.5.0
	 */
	public function remove_widgets() {
		$hidden = hab_Modules_Settings::hidden_for_roles( $this->settings['hidden'], wp_get_current_user()->roles );

		foreach ( $hidden as $id ) {
			if ( 'welcome_panel' === $id ) {
				remove_action( 'welcome_panel', 'wp_welcome_panel' );
				continue;
			}
			foreach ( array( 'normal', 'side', 'column3', 'column4', 'advanced' ) as $context ) {
				remove_meta_box( $id, 'dashboard', $context );
			}
		}
	}
}
