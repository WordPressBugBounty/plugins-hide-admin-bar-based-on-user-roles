<?php
/**
 * Admin bar inside the dashboard module.
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
 * Hides the admin bar inside wp-admin for the selected roles.
 *
 * WordPress always shows the admin bar in the dashboard and offers no setting to turn it off
 * there, so this hides it with CSS. On screens 782px wide and narrower the bar stays, because
 * it holds the button that opens the admin menu.
 *
 * @since 7.4.0
 */
class hab_Dashboard_Admin_Bar {

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

		add_action( 'admin_head', array( $this, 'print_styles' ) );
	}

	/**
	 * Print the CSS that hides the bar, for users with a selected role.
	 *
	 * @since 7.4.0
	 */
	public function print_styles() {
		$user = wp_get_current_user();
		if ( ! array_intersect( $this->settings['roles'], (array) $user->roles ) ) {
			return;
		}
		?>
		<style id="hab-dashboard-admin-bar">
			@media screen and (min-width: 783px) {
				html {
					--wp-admin--admin-bar--height: 0px !important;
				}
				html.wp-toolbar {
					padding-top: 0 !important;
				}
				#wpadminbar {
					display: none !important;
				}
				.interface-interface-skeleton,
				.woocommerce-layout__header {
					top: 0 !important;
				}
			}
		</style>
		<?php
	}
}
