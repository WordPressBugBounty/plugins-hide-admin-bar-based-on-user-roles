<?php
/**
 * Settings for the optional modules.
 *
 * The modules keep their settings in their own option, hab_modules. The original admin bar
 * rules in hab_settings are never read or written here.
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
 * Reads, validates and cleans the hab_modules option.
 *
 * @since 7.4.0
 */
class hab_Modules_Settings {

	/**
	 * Name of the option that holds every module's settings.
	 *
	 * @var string
	 */
	const OPTION = 'hab_modules';

	/**
	 * Default settings. Every module starts switched off.
	 *
	 * @since 7.4.0
	 * @return array
	 */
	public static function defaults() {
		return array(
			'dashboard_access'    => array(
				'enabled'       => false,
				'roles'         => array(),
				'redirect_url'  => '',
				'allow_profile' => false,
			),
			'dashboard_admin_bar' => array(
				'enabled' => false,
				'roles'   => array(),
			),
			'login_redirect'      => array(
				'enabled' => false,
				'urls'    => array(),
			),
			'strict_mode'         => array(
				'enabled' => false,
			),
			'switch_user'         => array(
				'enabled' => false,
			),
			'admin_menu'          => array(
				'enabled' => false,
				'hidden'  => array(),
			),
			'admin_bar_items'     => array(
				'enabled' => false,
				'hidden'  => array(),
			),
			'admin_notices'       => array(
				'enabled' => false,
				'roles'   => array(),
			),
			'dashboard_widgets'   => array(
				'enabled' => false,
				'hidden'  => array(),
			),
		);
	}

	/**
	 * The saved settings, filled in with the defaults.
	 *
	 * @since 7.4.0
	 * @return array
	 */
	public static function get() {
		$stored   = get_option( self::OPTION, array() );
		$stored   = is_array( $stored ) ? $stored : array();
		$settings = array();

		foreach ( self::defaults() as $section => $defaults ) {
			$values               = ( isset( $stored[ $section ] ) && is_array( $stored[ $section ] ) ) ? $stored[ $section ] : array();
			$settings[ $section ] = array_merge( $defaults, array_intersect_key( $values, $defaults ) );

			$settings[ $section ]['enabled'] = ! empty( $settings[ $section ]['enabled'] );
			if ( isset( $defaults['roles'] ) ) {
				$settings[ $section ]['roles'] = array_values( array_filter( (array) $settings[ $section ]['roles'], 'is_string' ) );
			}
			if ( isset( $defaults['urls'] ) ) {
				$settings[ $section ]['urls'] = array_filter( (array) $settings[ $section ]['urls'], 'is_string' );
			}
			if ( isset( $defaults['hidden'] ) ) {
				$settings[ $section ]['hidden'] = self::role_map( $settings[ $section ]['hidden'] );
			}
		}

		return $settings;
	}

	/**
	 * Sanitize callback for register_setting().
	 *
	 * Each settings tab posts only its own sections, listed in "sections". The other sections keep
	 * their saved values. Without "sections" (an update_option() call, or WordPress running the
	 * callback a second time on the first save), every section is cleaned from the value given.
	 *
	 * @since 7.4.0
	 * @param mixed $input The submitted value.
	 * @return array
	 */
	public static function sanitize( $input ) {
		$input    = is_array( $input ) ? $input : array();
		$settings = self::get();
		$sections = array_keys( self::defaults() );

		if ( isset( $input['sections'] ) ) {
			$sections = array_intersect( (array) $input['sections'], $sections );
		}

		foreach ( $sections as $section ) {
			$values               = ( isset( $input[ $section ] ) && is_array( $input[ $section ] ) ) ? $input[ $section ] : array();
			$settings[ $section ] = call_user_func( array( __CLASS__, 'sanitize_' . $section ), $values );
		}

		return $settings;
	}

	/**
	 * Clean the dashboard access settings.
	 *
	 * @since 7.4.0
	 * @param array $values Submitted values.
	 * @return array
	 */
	private static function sanitize_dashboard_access( $values ) {
		$roles        = self::sanitize_roles( isset( $values['roles'] ) ? $values['roles'] : array() );
		$roles        = array_values( array_diff( $roles, self::admin_roles() ) );
		$redirect_url = self::sanitize_url(
			isset( $values['redirect_url'] ) ? $values['redirect_url'] : '',
			__( 'Dashboard access', 'hide-admin-bar-based-on-user-roles' )
		);

		// Sending a blocked user to another dashboard page would loop.
		if ( '' !== $redirect_url && self::is_admin_url( $redirect_url ) ) {
			self::add_error(
				'dashboard_access_redirect',
				__( 'Dashboard access: pick a page outside the dashboard, or leave the field empty to use your home page.', 'hide-admin-bar-based-on-user-roles' )
			);
			$redirect_url = '';
		}

		return array(
			'enabled'       => ! empty( $values['enabled'] ),
			'roles'         => $roles,
			'redirect_url'  => $redirect_url,
			'allow_profile' => ! empty( $values['allow_profile'] ),
		);
	}

	/**
	 * Clean the settings for the admin bar inside the dashboard.
	 *
	 * @since 7.4.0
	 * @param array $values Submitted values.
	 * @return array
	 */
	private static function sanitize_dashboard_admin_bar( $values ) {
		return array(
			'enabled' => ! empty( $values['enabled'] ),
			'roles'   => self::sanitize_roles( isset( $values['roles'] ) ? $values['roles'] : array() ),
		);
	}

	/**
	 * Clean the login redirect settings.
	 *
	 * @since 7.4.0
	 * @param array $values Submitted values.
	 * @return array
	 */
	private static function sanitize_login_redirect( $values ) {
		$names = wp_roles()->get_names();
		$urls  = array();

		if ( isset( $values['urls'] ) && is_array( $values['urls'] ) ) {
			foreach ( $values['urls'] as $role => $url ) {
				if ( ! isset( $names[ $role ] ) ) {
					continue;
				}
				/* translators: %s: user role name, such as Subscriber. */
				$label = sprintf( __( 'Login redirect for %s', 'hide-admin-bar-based-on-user-roles' ), translate_user_role( $names[ $role ] ) );
				$url   = self::sanitize_url( $url, $label );
				if ( '' !== $url ) {
					$urls[ $role ] = $url;
				}
			}
		}

		return array(
			'enabled' => ! empty( $values['enabled'] ),
			'urls'    => $urls,
		);
	}

	/**
	 * Clean the strict mode settings.
	 *
	 * @since 7.4.0
	 * @param array $values Submitted values.
	 * @return array
	 */
	private static function sanitize_strict_mode( $values ) {
		return array(
			'enabled' => ! empty( $values['enabled'] ),
		);
	}

	/**
	 * Clean the switch to user settings.
	 *
	 * @since 7.5.0
	 * @param array $values Submitted values.
	 * @return array
	 */
	private static function sanitize_switch_user( $values ) {
		return array(
			'enabled' => ! empty( $values['enabled'] ),
		);
	}

	/**
	 * Clean the admin menu settings.
	 *
	 * @since 7.5.0
	 * @param array $values Submitted values.
	 * @return array
	 */
	private static function sanitize_admin_menu( $values ) {
		return array(
			'enabled' => ! empty( $values['enabled'] ),
			'hidden'  => self::sanitize_role_map( isset( $values['hidden'] ) ? $values['hidden'] : array() ),
		);
	}

	/**
	 * Clean the admin bar items settings.
	 *
	 * @since 7.5.0
	 * @param array $values Submitted values.
	 * @return array
	 */
	private static function sanitize_admin_bar_items( $values ) {
		return array(
			'enabled' => ! empty( $values['enabled'] ),
			'hidden'  => self::sanitize_role_map( isset( $values['hidden'] ) ? $values['hidden'] : array() ),
		);
	}

	/**
	 * Clean the admin notices settings.
	 *
	 * @since 7.5.0
	 * @param array $values Submitted values.
	 * @return array
	 */
	private static function sanitize_admin_notices( $values ) {
		return array(
			'enabled' => ! empty( $values['enabled'] ),
			'roles'   => self::sanitize_roles( isset( $values['roles'] ) ? $values['roles'] : array() ),
		);
	}

	/**
	 * Clean the dashboard widgets settings.
	 *
	 * @since 7.5.0
	 * @param array $values Submitted values.
	 * @return array
	 */
	private static function sanitize_dashboard_widgets( $values ) {
		return array(
			'enabled' => ! empty( $values['enabled'] ),
			'hidden'  => self::sanitize_role_map( isset( $values['hidden'] ) ? $values['hidden'] : array() ),
		);
	}

	/**
	 * Clean a list of hidden items per role: existing roles only, plain-text item IDs.
	 *
	 * @since 7.5.0
	 * @param mixed $map Submitted map of role => item IDs.
	 * @return array
	 */
	private static function sanitize_role_map( $map ) {
		$names = wp_roles()->get_names();
		$clean = array();

		foreach ( self::role_map( $map ) as $role => $items ) {
			if ( ! isset( $names[ $role ] ) ) {
				continue;
			}
			$items = array_values( array_unique( array_filter( array_map( 'sanitize_text_field', $items ), 'strlen' ) ) );
			if ( $items ) {
				$clean[ $role ] = array_slice( $items, 0, 500 );
			}
		}

		return $clean;
	}

	/**
	 * Shape a stored or submitted value as role => list of strings.
	 *
	 * @since 7.5.0
	 * @param mixed $map Value to shape.
	 * @return array
	 */
	private static function role_map( $map ) {
		$shaped = array();
		if ( ! is_array( $map ) ) {
			return $shaped;
		}

		foreach ( $map as $role => $items ) {
			if ( is_string( $role ) && is_array( $items ) ) {
				$shaped[ $role ] = array_values( array_filter( $items, 'is_string' ) );
			}
		}

		return $shaped;
	}

	/**
	 * Every item hidden for any of the given roles.
	 *
	 * @since 7.5.0
	 * @param array    $hidden Map of role => item IDs.
	 * @param string[] $roles  The user's roles.
	 * @return string[]
	 */
	public static function hidden_for_roles( $hidden, $roles ) {
		$items = array();
		foreach ( (array) $roles as $role ) {
			if ( isset( $hidden[ $role ] ) ) {
				$items = array_merge( $items, $hidden[ $role ] );
			}
		}

		return array_values( array_unique( $items ) );
	}

	/**
	 * Keep only roles that exist on this site.
	 *
	 * @since 7.4.0
	 * @param mixed $roles Submitted role keys.
	 * @return string[]
	 */
	private static function sanitize_roles( $roles ) {
		$roles = array_filter( (array) $roles, 'is_string' );

		return array_values( array_intersect( array_keys( wp_roles()->get_names() ), $roles ) );
	}

	/**
	 * Turn a submitted address into a full URL on this site.
	 *
	 * Paths such as /my-account/ are relative to the home page. Addresses on other sites are
	 * refused, because WordPress won't redirect to them.
	 *
	 * @since 7.4.0
	 * @param mixed  $raw   Submitted address.
	 * @param string $label Name of the setting, for the error message.
	 * @return string The URL, or an empty string.
	 */
	private static function sanitize_url( $raw, $label ) {
		$raw = is_string( $raw ) ? trim( $raw ) : '';
		if ( '' === $raw ) {
			return '';
		}

		if ( '/' === substr( $raw, 0, 1 ) && '//' !== substr( $raw, 0, 2 ) ) {
			$raw = home_url( $raw );
		}

		$url = esc_url_raw( $raw, array( 'http', 'https' ) );
		if ( '' === $url || '' === wp_validate_redirect( $url, '' ) ) {
			self::add_error(
				'url_' . md5( $label ),
				sprintf(
					/* translators: 1: name of the setting, 2: example address on this site. */
					__( '%1$s: enter a page on this site, such as %2$s', 'hide-admin-bar-based-on-user-roles' ),
					$label,
					home_url( '/' )
				)
			);
			return '';
		}

		return $url;
	}

	/**
	 * Whether a URL points inside wp-admin.
	 *
	 * @since 7.4.0
	 * @param string $url URL to check.
	 * @return bool
	 */
	public static function is_admin_url( $url ) {
		$path       = '/' . ltrim( (string) wp_parse_url( $url, PHP_URL_PATH ), '/' );
		$admin_path = '/' . ltrim( (string) wp_parse_url( admin_url(), PHP_URL_PATH ), '/' );

		return 0 === strpos( trailingslashit( $path ), $admin_path );
	}

	/**
	 * Roles that can manage the site. Dashboard access never blocks them.
	 *
	 * @since 7.4.0
	 * @return string[]
	 */
	public static function admin_roles() {
		$roles = array();
		foreach ( wp_roles()->role_objects as $key => $role ) {
			if ( $role->has_cap( 'manage_options' ) ) {
				$roles[] = $key;
			}
		}

		return $roles;
	}

	/**
	 * Show a validation message once the settings page reloads.
	 *
	 * @since 7.4.0
	 * @param string $code    Unique code for the message.
	 * @param string $message Message text.
	 */
	private static function add_error( $code, $message ) {
		if ( function_exists( 'add_settings_error' ) ) {
			add_settings_error( self::OPTION, 'hab_' . $code, $message, 'error' );
		}
	}
}
