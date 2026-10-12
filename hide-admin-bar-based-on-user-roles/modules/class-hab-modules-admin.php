<?php
/**
 * Settings pages for the optional modules.
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
 * Adds the "Dashboard & login", "Admin cleanup" and "Compatibility" tabs next to the original
 * settings page.
 *
 * Each tab is its own page under Settings, hidden from the menu so that Settings keeps a single
 * "Hide Admin Bar Settings" item. The original page shows the tabs through the hab_settings_tabs
 * action.
 *
 * @since 7.4.0
 */
class hab_Modules_Admin {

	/**
	 * Slug of the original settings page.
	 *
	 * @var string
	 */
	const MAIN_PAGE = 'hide-admin-bar-settings';

	/**
	 * Register the hooks.
	 *
	 * @since 7.4.0
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'register_pages' ) );
		add_action( 'admin_head', array( $this, 'hide_pages_from_menu' ) );
		add_filter( 'submenu_file', array( $this, 'highlight_menu_item' ) );
		add_action( 'admin_init', array( $this, 'register_setting' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'hab_settings_tabs', array( $this, 'render_tabs' ) );

		hab_Admin_Items::register();
	}

	/**
	 * The module pages and the settings sections each one shows.
	 *
	 * @since 7.4.0
	 * @return array
	 */
	private function pages() {
		return array(
			'hide-admin-bar-dashboard'     => array(
				'title'    => __( 'Dashboard & login', 'hide-admin-bar-based-on-user-roles' ),
				'sections' => array( 'dashboard_access', 'dashboard_admin_bar', 'login_redirect', 'switch_user' ),
			),
			hab_Admin_Items::PAGE          => array(
				'title'    => __( 'Admin cleanup', 'hide-admin-bar-based-on-user-roles' ),
				'sections' => array( 'admin_menu', 'admin_bar_items', 'admin_notices', 'dashboard_widgets' ),
			),
			'hide-admin-bar-compatibility' => array(
				'title'    => __( 'Compatibility', 'hide-admin-bar-based-on-user-roles' ),
				'sections' => array( 'strict_mode' ),
			),
		);
	}

	/**
	 * Slug of the module page being viewed, or an empty string.
	 *
	 * @since 7.4.0
	 * @return string
	 */
	private function current_page() {
		global $plugin_page;

		$pages = $this->pages();

		return ( is_string( $plugin_page ) && isset( $pages[ $plugin_page ] ) ) ? $plugin_page : '';
	}

	/**
	 * Register the module pages under Settings.
	 *
	 * @since 7.4.0
	 */
	public function register_pages() {
		foreach ( $this->pages() as $slug => $page ) {
			add_options_page(
				__( 'Hide Admin Bar Settings', 'hide-admin-bar-based-on-user-roles' ),
				$page['title'],
				'manage_options',
				$slug,
				array( $this, 'render_page' )
			);
		}
	}

	/**
	 * Take the module pages out of the Settings menu. They stay reachable through the tabs.
	 *
	 * This runs on admin_head, after WordPress has checked access to the page.
	 *
	 * @since 7.4.0
	 */
	public function hide_pages_from_menu() {
		foreach ( array_keys( $this->pages() ) as $slug ) {
			remove_submenu_page( 'options-general.php', $slug );
		}
	}

	/**
	 * Keep "Hide Admin Bar Settings" highlighted in the menu on the module pages.
	 *
	 * @since 7.4.0
	 * @param string|null $submenu_file The submenu item to highlight.
	 * @return string|null
	 */
	public function highlight_menu_item( $submenu_file ) {
		return '' !== $this->current_page() ? self::MAIN_PAGE : $submenu_file;
	}

	/**
	 * Register the hab_modules option with the Settings API.
	 *
	 * @since 7.4.0
	 */
	public function register_setting() {
		register_setting(
			'hab_modules',
			hab_Modules_Settings::OPTION,
			array(
				'sanitize_callback' => array( 'hab_Modules_Settings', 'sanitize' ),
			)
		);
	}

	/**
	 * Load the styles: the tabs on the original page, and the full page styles on the module pages.
	 *
	 * @since 7.4.0
	 * @param string $hook_suffix The current admin page.
	 */
	public function enqueue_styles( $hook_suffix ) {
		$on_module_page = '' !== $this->current_page();

		if ( ! $on_module_page && 'settings_page_' . self::MAIN_PAGE !== $hook_suffix ) {
			return;
		}

		$plugin_url = plugin_dir_url( __DIR__ );

		if ( $on_module_page ) {
			wp_enqueue_style( 'hide-admin-bar-based-on-user-roles-admin', $plugin_url . 'admin/css/hide-admin-bar-based-on-user-roles-admin.css', array( 'dashicons' ), HIDE_ADMIN_BAR_BASED_ON_USER_ROLES );
		}

		wp_enqueue_style( 'hab-modules-admin', $plugin_url . 'modules/css/modules-admin.css', array(), HIDE_ADMIN_BAR_BASED_ON_USER_ROLES );
	}

	/**
	 * Print the tabs that link the settings pages.
	 *
	 * @since 7.4.0
	 * @param string $current Slug of the page being viewed.
	 */
	public function render_tabs( $current = '' ) {
		$tabs = array( self::MAIN_PAGE => __( 'Admin bar', 'hide-admin-bar-based-on-user-roles' ) );
		foreach ( $this->pages() as $slug => $page ) {
			$tabs[ $slug ] = $page['title'];
		}
		?>
		<nav class="hab-tabs" aria-label="<?php esc_attr_e( 'Hide Admin Bar settings', 'hide-admin-bar-based-on-user-roles' ); ?>">
			<?php foreach ( $tabs as $slug => $label ) : ?>
				<a class="hab-tab<?php echo $slug === $current ? ' is-active' : ''; ?>" href="<?php echo esc_url( admin_url( 'options-general.php?page=' . $slug ) ); ?>"<?php echo $slug === $current ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $label ); ?></a>
			<?php endforeach; ?>
		</nav>
		<?php
	}

	/**
	 * Render a module page.
	 *
	 * @since 7.4.0
	 */
	public function render_page() {
		$page = $this->current_page();
		if ( '' === $page ) {
			return;
		}

		$pages        = $this->pages();
		$sections     = $pages[ $page ]['sections'];
		$settings     = hab_Modules_Settings::get();
		$roles        = wp_roles()->get_names();
		$admin_roles  = hab_Modules_Settings::admin_roles();
		$main_page    = admin_url( 'options-general.php?page=' . self::MAIN_PAGE );
		$switched_off = defined( 'HAB_DISABLE_MODULES' ) && HAB_DISABLE_MODULES;

		include __DIR__ . '/partials/settings-page.php';
	}
}
