<?php
/**
 * Admin notices module.
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
 * Hides the notices that WordPress, themes and plugins show at the top of dashboard screens,
 * for the selected roles.
 *
 * It removes everything hooked to the admin notice actions just before WordPress prints them.
 * Messages a screen prints inside its own content aren't affected.
 *
 * @since 7.5.0
 */
class hab_Admin_Notices {

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

		// in_admin_header runs just before WordPress prints the notices.
		add_action( 'in_admin_header', array( $this, 'remove_notices' ), PHP_INT_MAX );
	}

	/**
	 * Remove the notices for users with a selected role.
	 *
	 * @since 7.5.0
	 */
	public function remove_notices() {
		if ( ! array_intersect( $this->settings['roles'], (array) wp_get_current_user()->roles ) ) {
			return;
		}

		foreach ( array( 'admin_notices', 'all_admin_notices', 'user_admin_notices', 'network_admin_notices' ) as $hook ) {
			remove_all_actions( $hook );
		}
	}
}
