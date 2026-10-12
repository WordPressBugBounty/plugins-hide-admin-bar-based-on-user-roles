<?php
/**
 * Dashboard widgets settings.
 *
 * @link       https://wpankit.com/
 * @since      7.5.0
 *
 * @package    hab_Hide_Admin_Bar_Based_On_User_Roles
 * @subpackage hab_Hide_Admin_Bar_Based_On_User_Roles/modules/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$hab_values = $settings['dashboard_widgets'];
?>
<section class="hab-card hab-module" aria-labelledby="hab-dashboard-widgets-title">
	<div class="hab-card-header">
		<h2 id="hab-dashboard-widgets-title"><?php esc_html_e( 'Dashboard widgets', 'hide-admin-bar-based-on-user-roles' ); ?></h2>
		<p><?php esc_html_e( 'Choose which boxes each role sees on the Dashboard home screen.', 'hide-admin-bar-based-on-user-roles' ); ?></p>
	</div>

	<div class="hab-setting">
		<div class="hab-setting-text">
			<label class="hab-setting-title" for="hab-dashboard-widgets-enabled"><?php esc_html_e( 'Hide dashboard widgets', 'hide-admin-bar-based-on-user-roles' ); ?></label>
			<p class="hab-setting-desc" id="hab-dashboard-widgets-enabled-desc">
				<?php
				if ( hab_Admin_Items::dashboard_seen() ) {
					esc_html_e( 'Widgets from your plugins are listed as they appeared the last time you opened the Dashboard.', 'hide-admin-bar-based-on-user-roles' );
				} else {
					printf(
						/* translators: %s: link to the Dashboard. */
						esc_html__( 'Only WordPress’s own widgets are listed so far. Open the %s once and widgets from your plugins appear here too.', 'hide-admin-bar-based-on-user-roles' ),
						'<a href="' . esc_url( admin_url( 'index.php' ) ) . '">' . esc_html__( 'Dashboard', 'hide-admin-bar-based-on-user-roles' ) . '</a>'
					);
				}
				?>
			</p>
		</div>
		<input type="checkbox" class="hab-switch hab-module-toggle" id="hab-dashboard-widgets-enabled" name="hab_modules[dashboard_widgets][enabled]" value="1" role="switch" aria-describedby="hab-dashboard-widgets-enabled-desc" <?php checked( $hab_values['enabled'] ); ?>>
	</div>

	<div class="hab-module-body">
		<div class="hab-setting hab-setting-stacked">
			<p class="hab-setting-desc"><?php esc_html_e( 'Tick a widget in a role’s column to hide it for that role.', 'hide-admin-bar-based-on-user-roles' ); ?></p>
			<?php
			$hab_panel_section = 'dashboard_widgets';
			$hab_panel_items   = hab_Admin_Items::dashboard_widgets();
			$hab_panel_hidden  = $hab_values['hidden'];
			$hab_grid_label    = __( 'Widget', 'hide-admin-bar-based-on-user-roles' );
			require __DIR__ . '/role-grid.php';
			?>
		</div>
	</div>
</section>
