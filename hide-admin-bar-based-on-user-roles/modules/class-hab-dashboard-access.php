<?php
/**
 * Dashboard access module.
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
 * Keeps the selected roles out of wp-admin and sends them to another page instead.
 *
 * Administrators are never blocked, and the background requests that themes and plugins send
 * to admin-ajax.php, admin-post.php and async-upload.php keep working.
 *
 * @since 7.4.0
 */
class hab_Dashboard_Access {

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

		add_action( 'admin_init', array( $this, 'maybe_redirect' ), 0 );
		// WordPress refuses screens the user can't use, such as Posts for a subscriber, before
		// admin_init runs. Send blocked users to the chosen page from those screens too.
		add_action( 'admin_page_access_denied', array( $this, 'maybe_redirect' ), 0 );
	}

	/**
	 * Redirect a blocked user who opens a dashboard page.
	 *
	 * @since 7.4.0
	 */
	public function maybe_redirect() {
		global $pagenow;

		if ( wp_doing_ajax() || wp_doing_cron() || ! is_user_logged_in() ) {
			return;
		}

		if ( in_array( $pagenow, array( 'admin-post.php', 'async-upload.php' ), true ) ) {
			return;
		}

		if ( current_user_can( 'manage_options' ) || is_super_admin() ) {
			return;
		}

		if ( $this->settings['allow_profile'] && 'profile.php' === $pagenow ) {
			return;
		}

		$user = wp_get_current_user();
		if ( ! array_intersect( $this->settings['roles'], (array) $user->roles ) ) {
			return;
		}

		wp_safe_redirect( $this->redirect_url() );
		exit;
	}

	/**
	 * Where blocked users are sent: the chosen page, or the home page.
	 *
	 * @since 7.4.0
	 * @return string
	 */
	private function redirect_url() {
		$home = home_url( '/' );
		$url  = wp_validate_redirect( $this->settings['redirect_url'], $home );

		// A dashboard address would send the user straight back here.
		if ( '' === $url || hab_Modules_Settings::is_admin_url( $url ) ) {
			return $home;
		}

		return $url;
	}
}
