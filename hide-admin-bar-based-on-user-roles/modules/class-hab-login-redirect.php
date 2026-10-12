<?php
/**
 * Login redirect module.
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
 * Sends each role to its own page after logging in through the WordPress login form.
 *
 * Only the default destination, the dashboard, is replaced. A login link that asks for a
 * specific page, such as "Log in to comment" or a link to edit a post, still goes there.
 *
 * @since 7.4.0
 */
class hab_Login_Redirect {

	/**
	 * Module settings.
	 *
	 * @var array
	 */
	private $settings;

	/**
	 * Register the hook.
	 *
	 * @since 7.4.0
	 * @param array $settings Module settings from hab_Modules_Settings.
	 */
	public function __construct( $settings ) {
		$this->settings = $settings;

		add_filter( 'login_redirect', array( $this, 'redirect' ), 99, 3 );
	}

	/**
	 * Replace the default destination with the page set for the user's role.
	 *
	 * @since 7.4.0
	 * @param string           $redirect_to The destination WordPress will use.
	 * @param string           $requested   The destination the login link asked for, if any.
	 * @param WP_User|WP_Error $user        The user who logged in, or the login error.
	 * @return string
	 */
	public function redirect( $redirect_to, $requested, $user ) {
		if ( ! $user instanceof WP_User || ! $user->exists() ) {
			return $redirect_to;
		}

		if ( ! $this->is_default_destination( $requested ) ) {
			return $redirect_to;
		}

		foreach ( (array) $user->roles as $role ) {
			if ( ! empty( $this->settings['urls'][ $role ] ) ) {
				return $this->settings['urls'][ $role ];
			}
		}

		return $redirect_to;
	}

	/**
	 * Whether the login link asked for nothing in particular, or just the dashboard home.
	 *
	 * @since 7.4.0
	 * @param string $requested The requested destination.
	 * @return bool
	 */
	private function is_default_destination( $requested ) {
		if ( ! is_string( $requested ) || '' === $requested ) {
			return true;
		}

		$path       = '/' . ltrim( (string) wp_parse_url( $requested, PHP_URL_PATH ), '/' );
		$admin_path = '/' . ltrim( (string) wp_parse_url( admin_url(), PHP_URL_PATH ), '/' );

		return in_array( $path, array( untrailingslashit( $admin_path ), $admin_path, $admin_path . 'index.php' ), true );
	}
}
