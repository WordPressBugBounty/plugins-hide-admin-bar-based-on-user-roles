<?php
/**
 * Strict mode module.
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

/**
 * Keeps the admin bar hidden when another plugin turns it back on.
 *
 * The original rules hide the bar on the "wp" action. Plugins such as BuddyPress, wpForo or
 * WC Vendors can switch it back on later through the show_admin_bar filter. Strict mode applies
 * the same rules again on that filter, after every other plugin. It reads hab_settings but never
 * changes it.
 *
 * @since 7.4.0
 */
class hab_Strict_Mode {

	/**
	 * Whether the rules hide the bar for this request, once known.
	 *
	 * @var bool|null
	 */
	private $hide = null;

	/**
	 * Register the hook.
	 *
	 * @since 7.4.0
	 */
	public function __construct() {
		add_filter( 'show_admin_bar', array( $this, 'filter' ), PHP_INT_MAX );
	}

	/**
	 * Turn the admin bar off when the Admin bar tab's rules say so.
	 *
	 * @since 7.4.0
	 * @param bool $show Whether the admin bar shows.
	 * @return bool
	 */
	public function filter( $show ) {
		if ( ! $show || is_admin() || ! did_action( 'init' ) ) {
			return $show;
		}

		if ( null === $this->hide ) {
			$this->hide = $this->rules_hide_admin_bar();
		}

		return $this->hide ? false : $show;
	}

	/**
	 * The same checks as hab_Hide_Admin_Bar_Based_On_User_Roles_Public::should_hide_admin_bar().
	 *
	 * @since 7.4.0
	 * @return bool
	 */
	private function rules_hide_admin_bar() {
		$settings = get_option( 'hab_settings' );
		if ( ! is_array( $settings ) ) {
			return false;
		}

		if ( isset( $settings['hab_disableforall'] ) && 'yes' === $settings['hab_disableforall'] ) {
			return true;
		}

		if ( isset( $settings['hab_userRoles'] ) && is_array( $settings['hab_userRoles'] ) ) {
			$user = wp_get_current_user();
			if ( array_intersect( $settings['hab_userRoles'], $user->roles ) ) {
				return true;
			}
		}

		if ( isset( $settings['hab_capabilities'] ) && is_string( $settings['hab_capabilities'] ) ) {
			$capabilities = array_filter( array_map( 'trim', explode( ',', $settings['hab_capabilities'] ) ), 'strlen' );
			foreach ( $capabilities as $capability ) {
				if ( current_user_can( $capability ) ) {
					return true;
				}
			}
		}

		if ( isset( $settings['hab_disableforallGuests'] ) && 'yes' === $settings['hab_disableforallGuests'] && ! is_user_logged_in() ) {
			return true;
		}

		return false;
	}
}
