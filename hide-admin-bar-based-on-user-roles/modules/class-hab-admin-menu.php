<?php
/**
 * Admin menu module.
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
 * Hides admin menu items for the selected roles.
 *
 * This tidies the menu only. The screens stay reachable by their address; Dashboard access is
 * the module that keeps a role out of wp-admin. Settings is never hidden, so the way back to
 * these settings stays.
 *
 * @since 7.5.0
 */
class hab_Admin_Menu {

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

		add_action( 'admin_menu', array( $this, 'hide_items' ), PHP_INT_MAX );
	}

	/**
	 * Remove the hidden items from the menu of the current user.
	 *
	 * Items are saved as "menu" for a top-level item and "menu|submenu" for a submenu item.
	 *
	 * @since 7.5.0
	 */
	public function hide_items() {
		global $menu, $submenu;

		$hidden = hab_Modules_Settings::hidden_for_roles( $this->settings['hidden'], wp_get_current_user()->roles );
		if ( ! $hidden || ! is_array( $menu ) ) {
			return;
		}

		$top     = array();
		$present = array();
		foreach ( $menu as $item ) {
			if ( ! empty( $item[2] ) ) {
				$present[] = hab_Admin_Items::menu_key( $item[2] );
			}
		}

		foreach ( $hidden as $id ) {
			$parts = explode( '|', $id, 2 );
			if ( 1 === count( $parts ) ) {
				$top[] = $id;
			} elseif ( ! in_array( $parts[0], $present, true ) ) {
				// WordPress moves some screens to the top level for users who can't open the parent
				// menu, such as Users > Profile, which subscribers see as Profile.
				$top[] = $parts[1];
			}
		}
		$top = array_diff( $top, array( 'options-general.php' ) );

		foreach ( $menu as $position => $item ) {
			if ( ! empty( $item[2] ) && in_array( hab_Admin_Items::menu_key( $item[2] ), $top, true ) ) {
				unset( $menu[ $position ] );
			}
		}

		foreach ( (array) $submenu as $parent => $items ) {
			$parent_key = hab_Admin_Items::menu_key( $parent );
			if ( 'options-general.php' === $parent_key ) {
				continue;
			}
			foreach ( (array) $items as $position => $item ) {
				if ( ! empty( $item[2] ) && in_array( $parent_key . '|' . hab_Admin_Items::menu_key( $item[2] ), $hidden, true ) ) {
					unset( $submenu[ $parent ][ $position ] );
				}
			}
		}
	}
}
