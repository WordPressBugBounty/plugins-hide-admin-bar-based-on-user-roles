<?php
/**
 * Admin menu settings.
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

$hab_values = $settings['admin_menu'];
?>
<section class="hab-card hab-module" aria-labelledby="hab-admin-menu-title">
	<div class="hab-card-header">
		<h2 id="hab-admin-menu-title"><?php esc_html_e( 'Admin menu', 'hide-admin-bar-based-on-user-roles' ); ?></h2>
		<p><?php esc_html_e( 'Hide menu items that a role doesn’t need, so the dashboard shows only what matters to them.', 'hide-admin-bar-based-on-user-roles' ); ?></p>
	</div>

	<div class="hab-setting">
		<div class="hab-setting-text">
			<label class="hab-setting-title" for="hab-admin-menu-enabled"><?php esc_html_e( 'Hide admin menu items', 'hide-admin-bar-based-on-user-roles' ); ?></label>
			<p class="hab-setting-desc" id="hab-admin-menu-enabled-desc">
				<?php
				printf(
					/* translators: %s: link to the Dashboard & login tab. */
					esc_html__( 'This tidies the menu but doesn’t lock the screens: someone who knows the address can still open them. To keep a role out of the dashboard, use Dashboard access on the %s tab.', 'hide-admin-bar-based-on-user-roles' ),
					'<a href="' . esc_url( admin_url( 'options-general.php?page=hide-admin-bar-dashboard' ) ) . '">' . esc_html__( 'Dashboard & login', 'hide-admin-bar-based-on-user-roles' ) . '</a>'
				);
				?>
			</p>
		</div>
		<input type="checkbox" class="hab-switch hab-module-toggle" id="hab-admin-menu-enabled" name="hab_modules[admin_menu][enabled]" value="1" role="switch" aria-describedby="hab-admin-menu-enabled-desc" <?php checked( $hab_values['enabled'] ); ?>>
	</div>

	<div class="hab-module-body">
		<div class="hab-setting hab-setting-stacked">
			<p class="hab-setting-desc"><?php esc_html_e( 'Tick an item in a role’s column to hide it for that role. Hiding a menu hides everything under it. Settings always stays, so you can get back here.', 'hide-admin-bar-based-on-user-roles' ); ?></p>
			<?php
			$hab_panel_section = 'admin_menu';
			$hab_panel_items   = hab_Admin_Items::admin_menu();
			$hab_panel_hidden  = $hab_values['hidden'];
			$hab_grid_label    = __( 'Menu item', 'hide-admin-bar-based-on-user-roles' );
			require __DIR__ . '/role-grid.php';
			?>
		</div>
	</div>
</section>
