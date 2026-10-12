<?php
/**
 * Switch to user module.
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
 * Lets administrators log in as another user to see the site as they do, then switch back.
 *
 * - Only users who can manage the site can switch, and only to users they can edit who can't
 *   manage the site themselves.
 * - Switching keeps a copy of the administrator's own login cookie in an HTTP-only cookie.
 *   Switching back only works while that cookie is still a valid login for an administrator,
 *   so the switched-to user can't use it to gain anything they didn't already have.
 * - Every switch link carries a nonce. A bar at the bottom of the screen shows who you're
 *   viewing as, because the admin bar may be hidden for that user.
 *
 * @since 7.5.0
 */
class hab_Switch_User {

	/**
	 * Register the hooks.
	 *
	 * @since 7.5.0
	 */
	public function __construct() {
		add_filter( 'user_row_actions', array( $this, 'row_action' ), 10, 2 );
		add_filter( 'manage_users_columns', array( $this, 'add_column' ) );
		add_filter( 'manage_users_custom_column', array( $this, 'render_column' ), 10, 3 );
		add_action( 'admin_head-users.php', array( $this, 'print_column_styles' ) );
		add_action( 'admin_post_hab_switch_to_user', array( $this, 'switch_to' ) );
		add_action( 'init', array( $this, 'maybe_switch_back' ), 1 );
		add_action( 'wp_footer', array( $this, 'print_switch_bar' ) );
		add_action( 'admin_footer', array( $this, 'print_switch_bar' ) );
		add_action( 'wp_logout', array( $this, 'forget_original_user' ) );
	}

	/**
	 * Name of the cookie that holds the administrator's own login.
	 *
	 * @since 7.5.0
	 * @return string
	 */
	private function cookie_name() {
		return 'hab_switch_back_' . COOKIEHASH;
	}

	/**
	 * Whether the current user may switch to a user.
	 *
	 * @since 7.5.0
	 * @param WP_User $user The user to switch to.
	 * @return bool
	 */
	private function can_switch_to( $user ) {
		return $user instanceof WP_User
			&& $user->exists()
			&& get_current_user_id() !== $user->ID
			&& current_user_can( 'manage_options' )
			&& current_user_can( 'edit_user', $user->ID )
			&& ! user_can( $user, 'manage_options' )
			&& ! is_super_admin( $user->ID );
	}

	/**
	 * Address that switches to a user.
	 *
	 * @since 7.5.0
	 * @param WP_User $user The user to switch to.
	 * @return string
	 */
	private function switch_to_url( $user ) {
		return wp_nonce_url(
			add_query_arg(
				array(
					'action'  => 'hab_switch_to_user',
					'user_id' => $user->ID,
				),
				admin_url( 'admin-post.php' )
			),
			'hab_switch_to_user_' . $user->ID
		);
	}

	/**
	 * Add "Switch to" under the user's name on the Users screen.
	 *
	 * @since 7.5.0
	 * @param string[] $actions Row actions.
	 * @param WP_User  $user    The user in the row.
	 * @return string[]
	 */
	public function row_action( $actions, $user ) {
		if ( $this->can_switch_to( $user ) ) {
			$actions['hab_switch_to'] = '<a href="' . esc_url( $this->switch_to_url( $user ) ) . '">' . esc_html__( 'Switch to', 'hide-admin-bar-based-on-user-roles' ) . '</a>';
		}

		return $actions;
	}

	/**
	 * Add a "Switch to" column to the Users screen, for quick access. It can be hidden from
	 * Screen Options like any other column.
	 *
	 * @since 7.5.0
	 * @param string[] $columns Column titles.
	 * @return string[]
	 */
	public function add_column( $columns ) {
		if ( current_user_can( 'manage_options' ) ) {
			$columns['hab_switch_to'] = __( 'Switch to', 'hide-admin-bar-based-on-user-roles' );
		}

		return $columns;
	}

	/**
	 * Show a "Switch to" button for the users the current user may switch to, and a dash for the rest.
	 *
	 * @since 7.5.0
	 * @param string $output  Column content so far.
	 * @param string $column  Column name.
	 * @param int    $user_id User in the row.
	 * @return string
	 */
	public function render_column( $output, $column, $user_id ) {
		if ( 'hab_switch_to' !== $column ) {
			return $output;
		}

		$user = get_userdata( $user_id );
		if ( ! $user || ! $this->can_switch_to( $user ) ) {
			return '<span aria-hidden="true">&#8212;</span><span class="screen-reader-text">' . esc_html__( 'Not available', 'hide-admin-bar-based-on-user-roles' ) . '</span>';
		}

		/* translators: %s: user's display name. */
		$label = sprintf( __( 'Switch to %s', 'hide-admin-bar-based-on-user-roles' ), $user->display_name );

		return '<a class="button button-small" href="' . esc_url( $this->switch_to_url( $user ) ) . '" aria-label="' . esc_attr( $label ) . '">' . esc_html__( 'Switch to', 'hide-admin-bar-based-on-user-roles' ) . '</a>';
	}

	/**
	 * Keep the "Switch to" column narrow.
	 *
	 * @since 7.5.0
	 */
	public function print_column_styles() {
		echo '<style>.fixed .column-hab_switch_to { width: 7.5em; }</style>';
	}

	/**
	 * Log in as the chosen user, keeping the administrator's login for switching back.
	 *
	 * @since 7.5.0
	 */
	public function switch_to() {
		$user_id = isset( $_GET['user_id'] ) ? absint( $_GET['user_id'] ) : 0;
		check_admin_referer( 'hab_switch_to_user_' . $user_id );

		$user = get_userdata( $user_id );
		if ( ! $user || ! $this->can_switch_to( $user ) ) {
			wp_die( esc_html__( 'You can’t switch to this user.', 'hide-admin-bar-based-on-user-roles' ), '', array( 'response' => 403 ) );
		}

		// Checked against the current login before it's kept.
		$own_login = isset( $_COOKIE[ LOGGED_IN_COOKIE ] ) ? wp_unslash( $_COOKIE[ LOGGED_IN_COOKIE ] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		if ( get_current_user_id() !== wp_validate_auth_cookie( $own_login, 'logged_in' ) ) {
			wp_die( esc_html__( 'Your login couldn’t be checked. Log out, log in again and try once more.', 'hide-admin-bar-based-on-user-roles' ), '', array( 'response' => 403 ) );
		}

		setcookie( $this->cookie_name(), $own_login, 0, COOKIEPATH, COOKIE_DOMAIN, is_ssl(), true );

		// Only the cookies are cleared, so the administrator's session stays valid for switching back.
		wp_clear_auth_cookie();
		wp_set_auth_cookie( $user->ID, false );
		wp_set_current_user( $user->ID );

		wp_safe_redirect( admin_url() );
		exit;
	}

	/**
	 * The administrator who switched to the current user, while their login is still valid.
	 *
	 * @since 7.5.0
	 * @return WP_User|null
	 */
	private function original_user() {
		$name = $this->cookie_name();
		if ( empty( $_COOKIE[ $name ] ) || ! is_user_logged_in() ) {
			return null;
		}

		$user_id = wp_validate_auth_cookie( wp_unslash( $_COOKIE[ $name ] ), 'logged_in' ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Validated as a login cookie.
		if ( ! $user_id || get_current_user_id() === $user_id ) {
			return null;
		}

		$user = get_userdata( $user_id );

		return ( $user && user_can( $user, 'manage_options' ) ) ? $user : null;
	}

	/**
	 * Address that switches back to the administrator.
	 *
	 * @since 7.5.0
	 * @return string
	 */
	private function switch_back_url() {
		return wp_nonce_url( add_query_arg( 'hab_switch_back', '1', home_url( '/' ) ), 'hab_switch_back' );
	}

	/**
	 * Switch back to the administrator when the switch back link is followed.
	 *
	 * This runs on the front end, so it works even for users who can't open wp-admin.
	 *
	 * @since 7.5.0
	 */
	public function maybe_switch_back() {
		if ( ! isset( $_GET['hab_switch_back'] ) || ! is_user_logged_in() ) {
			return;
		}
		check_admin_referer( 'hab_switch_back' );

		$original = $this->original_user();
		if ( ! $original ) {
			wp_die( esc_html__( 'Your administrator login has expired. Log out, then log in as yourself again.', 'hide-admin-bar-based-on-user-roles' ), '', array( 'response' => 403 ) );
		}

		$login = wp_parse_auth_cookie( wp_unslash( $_COOKIE[ $this->cookie_name() ] ), 'logged_in' ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.InputNotValidated -- Validated in original_user().

		// End the session that was started for the switched-to user.
		WP_Session_Tokens::get_instance( get_current_user_id() )->destroy( wp_get_session_token() );
		wp_clear_auth_cookie();
		$this->clear_cookie();

		// Resume the administrator's own session.
		wp_set_auth_cookie( $original->ID, false, '', $login['token'] );
		wp_set_current_user( $original->ID );

		wp_safe_redirect( admin_url( 'users.php' ) );
		exit;
	}

	/**
	 * When the switched-to user logs out, end the administrator's kept session too.
	 *
	 * @since 7.5.0
	 */
	public function forget_original_user() {
		$name = $this->cookie_name();
		if ( empty( $_COOKIE[ $name ] ) ) {
			return;
		}

		$cookie  = wp_unslash( $_COOKIE[ $name ] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Validated below.
		$user_id = wp_validate_auth_cookie( $cookie, 'logged_in' );
		$login   = wp_parse_auth_cookie( $cookie, 'logged_in' );
		if ( $user_id && ! empty( $login['token'] ) ) {
			WP_Session_Tokens::get_instance( $user_id )->destroy( $login['token'] );
		}

		$this->clear_cookie();
	}

	/**
	 * Delete the cookie that holds the administrator's login.
	 *
	 * @since 7.5.0
	 */
	private function clear_cookie() {
		setcookie( $this->cookie_name(), ' ', time() - YEAR_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN, is_ssl(), true );
		unset( $_COOKIE[ $this->cookie_name() ] );
	}

	/**
	 * Show who the administrator is viewing as, with a link to switch back.
	 *
	 * @since 7.5.0
	 */
	public function print_switch_bar() {
		$original = $this->original_user();
		if ( ! $original ) {
			return;
		}

		$user  = wp_get_current_user();
		$names = wp_roles()->get_names();
		$roles = array();
		foreach ( (array) $user->roles as $role ) {
			$roles[] = isset( $names[ $role ] ) ? translate_user_role( $names[ $role ] ) : $role;
		}
		?>
		<div id="hab-switch-bar" role="region" aria-label="<?php esc_attr_e( 'Switched user', 'hide-admin-bar-based-on-user-roles' ); ?>" style="position:fixed;left:16px;bottom:16px;z-index:100000;display:flex;flex-wrap:wrap;align-items:center;gap:6px 12px;max-width:calc(100% - 32px);box-sizing:border-box;padding:10px 14px;border-radius:8px;background:#1d2327;color:#fff;font:13px/1.4 -apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;box-shadow:0 4px 16px rgba(0,0,0,.25);">
			<span>
				<?php
				printf(
					/* translators: 1: user's display name, 2: user's roles. */
					esc_html__( 'Viewing as %1$s (%2$s)', 'hide-admin-bar-based-on-user-roles' ),
					'<strong>' . esc_html( $user->display_name ) . '</strong>',
					esc_html( implode( ', ', $roles ) )
				);
				?>
			</span>
			<a href="<?php echo esc_url( $this->switch_back_url() ); ?>" style="color:#ffc21a;font-weight:600;text-decoration:underline;">
				<?php
				/* translators: %s: administrator's display name. */
				printf( esc_html__( 'Switch back to %s', 'hide-admin-bar-based-on-user-roles' ), esc_html( $original->display_name ) );
				?>
			</a>
		</div>
		<?php
	}
}
