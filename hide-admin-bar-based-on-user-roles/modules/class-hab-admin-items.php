<?php
/**
 * Lists of admin menu items, admin bar items and dashboard widgets for the settings page.
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
 * Collects the items that the Admin cleanup tab offers to hide.
 *
 * The admin menu and admin bar are read on the Admin cleanup page itself, just before the
 * modules remove anything. Dashboard widgets are only registered on the Dashboard, so the list
 * is saved whenever an administrator opens it.
 *
 * @since 7.5.0
 */
class hab_Admin_Items {

	/**
	 * Slug of the Admin cleanup page.
	 *
	 * @var string
	 */
	const PAGE = 'hide-admin-bar-cleanup';

	/**
	 * Option that remembers the dashboard widgets seen on the Dashboard.
	 *
	 * @var string
	 */
	const WIDGETS_OPTION = 'hab_dashboard_widgets';

	/**
	 * Admin menu read on the Admin cleanup page.
	 *
	 * @var array|null
	 */
	private static $menu = null;

	/**
	 * Top-level admin bar items read on the Admin cleanup page, as id => label.
	 *
	 * @var array|null
	 */
	private static $nodes = null;

	/**
	 * Register the hooks that read the menu, the admin bar and the dashboard widgets.
	 *
	 * Each runs one step before the modules remove items, so the lists stay complete.
	 *
	 * @since 7.5.0
	 */
	public static function register() {
		add_action( 'admin_menu', array( __CLASS__, 'capture_menu' ), PHP_INT_MAX - 1 );
		add_action( 'admin_bar_menu', array( __CLASS__, 'capture_admin_bar' ), PHP_INT_MAX - 1 );
		add_action( 'wp_dashboard_setup', array( __CLASS__, 'capture_dashboard_widgets' ), PHP_INT_MAX - 1 );
	}

	/**
	 * Whether the Admin cleanup page is being viewed.
	 *
	 * @since 7.5.0
	 * @return bool
	 */
	private static function on_cleanup_page() {
		global $plugin_page;

		return self::PAGE === $plugin_page;
	}

	/**
	 * Read the admin menu.
	 *
	 * Settings is left out, so that nobody can hide the way back to these settings.
	 *
	 * @since 7.5.0
	 */
	public static function capture_menu() {
		global $menu, $submenu;

		if ( ! self::on_cleanup_page() ) {
			return;
		}

		// Same order as the menu on screen; WordPress sorts it after admin_menu.
		$ordered = (array) $menu;
		uksort( $ordered, 'strnatcasecmp' );

		$items = array();
		foreach ( $ordered as $item ) {
			if ( empty( $item[2] ) || ( isset( $item[4] ) && false !== strpos( $item[4], 'wp-menu-separator' ) ) ) {
				continue;
			}

			$key   = self::menu_key( $item[2] );
			$label = self::label( $item[0] );
			if ( '' === $label || 'options-general.php' === $key ) {
				continue;
			}

			$children = array();
			if ( ! empty( $submenu[ $item[2] ] ) ) {
				foreach ( $submenu[ $item[2] ] as $sub ) {
					$sub_label = empty( $sub[2] ) ? '' : self::label( $sub[0] );
					if ( '' !== $sub_label ) {
						$children[] = array(
							'id'    => $key . '|' . self::menu_key( $sub[2] ),
							'label' => $sub_label,
						);
					}
				}
			}

			$items[] = array(
				'id'       => $key,
				'label'    => $label,
				'children' => $children,
			);
		}

		self::$menu = $items;
	}

	/**
	 * Read the top-level admin bar items.
	 *
	 * @since 7.5.0
	 * @param WP_Admin_Bar $wp_admin_bar The admin bar.
	 */
	public static function capture_admin_bar( $wp_admin_bar ) {
		if ( ! is_admin() || ! self::on_cleanup_page() ) {
			return;
		}

		$nodes = array();
		foreach ( (array) $wp_admin_bar->get_nodes() as $node ) {
			if ( ! empty( $node->group ) || ! in_array( $node->parent, array( false, '', 'top-secondary' ), true ) ) {
				continue;
			}
			$nodes[ $node->id ] = self::label( $node->title );
		}

		self::$nodes = $nodes;
	}

	/**
	 * Remember the dashboard widgets an administrator sees on the Dashboard.
	 *
	 * The option is only written when the list changes.
	 *
	 * @since 7.5.0
	 */
	public static function capture_dashboard_widgets() {
		global $wp_meta_boxes;

		if ( ! current_user_can( 'manage_options' ) || empty( $wp_meta_boxes['dashboard'] ) ) {
			return;
		}

		$widgets = array();
		foreach ( (array) $wp_meta_boxes['dashboard'] as $priorities ) {
			foreach ( (array) $priorities as $boxes ) {
				foreach ( (array) $boxes as $id => $box ) {
					if ( is_array( $box ) && ! empty( $box['title'] ) ) {
						$widgets[ $id ] = self::label( $box['title'] );
					}
				}
			}
		}

		if ( get_option( self::WIDGETS_OPTION ) !== $widgets ) {
			update_option( self::WIDGETS_OPTION, $widgets, false );
		}
	}

	/**
	 * Admin menu items, each with its submenu items.
	 *
	 * @since 7.5.0
	 * @return array List of array( 'id', 'label', 'children' ).
	 */
	public static function admin_menu() {
		return is_array( self::$menu ) ? self::$menu : array();
	}

	/**
	 * Top-level admin bar items: WordPress's own, then any that plugins add.
	 *
	 * @since 7.5.0
	 * @return array List of array( 'id', 'label' ).
	 */
	public static function admin_bar_items() {
		$core = array(
			'wp-logo'         => __( 'WordPress logo menu', 'hide-admin-bar-based-on-user-roles' ),
			'site-name'       => __( 'Site name, with links to the site and the dashboard', 'hide-admin-bar-based-on-user-roles' ),
			'updates'         => __( 'Updates', 'hide-admin-bar-based-on-user-roles' ),
			'comments'        => __( 'Comments', 'hide-admin-bar-based-on-user-roles' ),
			'new-content'     => __( '+ New', 'hide-admin-bar-based-on-user-roles' ),
			'command-palette' => __( 'Command palette shortcut', 'hide-admin-bar-based-on-user-roles' ),
			'edit'            => __( 'Edit link on posts and pages', 'hide-admin-bar-based-on-user-roles' ),
			'site-editor'     => __( 'Edit site', 'hide-admin-bar-based-on-user-roles' ),
			'customize'       => __( 'Customize', 'hide-admin-bar-based-on-user-roles' ),
			'search'          => __( 'Search', 'hide-admin-bar-based-on-user-roles' ),
			'my-account'      => __( 'Account menu, with Edit profile and Log out', 'hide-admin-bar-based-on-user-roles' ),
		);
		if ( is_multisite() ) {
			$core = array_merge( array( 'my-sites' => __( 'My Sites', 'hide-admin-bar-based-on-user-roles' ) ), $core );
		}

		$items = array();
		foreach ( $core as $id => $label ) {
			$items[] = array(
				'id'    => $id,
				'label' => $label,
			);
		}

		// The button that opens the admin menu on phones must always stay.
		$skip = array( 'menu-toggle', 'recovery-mode' );
		foreach ( (array) self::$nodes as $id => $label ) {
			if ( ! isset( $core[ $id ] ) && ! in_array( $id, $skip, true ) ) {
				$items[] = array(
					'id'    => $id,
					'label' => '' === $label ? $id : $label,
				);
			}
		}

		return $items;
	}

	/**
	 * Dashboard widgets: WordPress's own, then any seen on the Dashboard.
	 *
	 * @since 7.5.0
	 * @return array List of array( 'id', 'label' ).
	 */
	public static function dashboard_widgets() {
		$widgets = array(
			'welcome_panel'         => __( 'Welcome', 'hide-admin-bar-based-on-user-roles' ),
			'dashboard_site_health' => __( 'Site Health Status', 'hide-admin-bar-based-on-user-roles' ),
			'dashboard_right_now'   => __( 'At a Glance', 'hide-admin-bar-based-on-user-roles' ),
			'dashboard_activity'    => __( 'Activity', 'hide-admin-bar-based-on-user-roles' ),
			'dashboard_quick_press' => __( 'Quick Draft', 'hide-admin-bar-based-on-user-roles' ),
			'dashboard_primary'     => __( 'WordPress Events and News', 'hide-admin-bar-based-on-user-roles' ),
		);

		$seen = get_option( self::WIDGETS_OPTION, array() );
		foreach ( (array) $seen as $id => $label ) {
			if ( ! isset( $widgets[ $id ] ) && is_string( $label ) && '' !== $label ) {
				$widgets[ $id ] = $label;
			}
		}

		$items = array();
		foreach ( $widgets as $id => $label ) {
			$items[] = array(
				'id'    => (string) $id,
				'label' => $label,
			);
		}

		return $items;
	}

	/**
	 * Whether any dashboard widgets have been seen on the Dashboard yet.
	 *
	 * @since 7.5.0
	 * @return bool
	 */
	public static function dashboard_seen() {
		return false !== get_option( self::WIDGETS_OPTION );
	}

	/**
	 * A menu slug in the form used to save and match it.
	 *
	 * Drops the "return" address that Appearance > Customize adds, which changes on every page.
	 *
	 * @since 7.5.0
	 * @param string $slug Menu slug.
	 * @return string
	 */
	public static function menu_key( $slug ) {
		$slug = html_entity_decode( (string) $slug, ENT_QUOTES, 'UTF-8' );

		return false === strpos( $slug, 'return=' ) ? $slug : remove_query_arg( 'return', $slug );
	}

	/**
	 * Plain text for a menu, admin bar or widget title, without counters and screen reader text.
	 *
	 * @since 7.5.0
	 * @param string $html Title HTML.
	 * @return string
	 */
	private static function label( $html ) {
		$html = preg_replace( '#<span[^>]*class="[^"]*(?:update-plugins|awaiting-mod|count-|screen-reader-text|menu-counter)[^"]*"[^>]*>.*?</span>#s', '', (string) $html );

		return trim( preg_replace( '/\s+/', ' ', wp_strip_all_tags( $html ) ) );
	}
}
