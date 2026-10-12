<?php
/**
 * Admin bar items settings.
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

$hab_values = $settings['admin_bar_items'];
?>
<section class="hab-card hab-module" aria-labelledby="hab-admin-bar-items-title">
	<div class="hab-card-header">
		<h2 id="hab-admin-bar-items-title"><?php esc_html_e( 'Admin bar items', 'hide-admin-bar-based-on-user-roles' ); ?></h2>
		<p><?php esc_html_e( 'Keep the admin bar, but remove the items a role doesn’t need, such as the WordPress logo, Comments or + New.', 'hide-admin-bar-based-on-user-roles' ); ?></p>
	</div>

	<div class="hab-setting">
		<div class="hab-setting-text">
			<label class="hab-setting-title" for="hab-admin-bar-items-enabled"><?php esc_html_e( 'Remove admin bar items', 'hide-admin-bar-based-on-user-roles' ); ?></label>
			<p class="hab-setting-desc" id="hab-admin-bar-items-enabled-desc"><?php esc_html_e( 'Applies on your site and in the dashboard. Some items, such as Customize and Search, only appear on your site.', 'hide-admin-bar-based-on-user-roles' ); ?></p>
		</div>
		<input type="checkbox" class="hab-switch hab-module-toggle" id="hab-admin-bar-items-enabled" name="hab_modules[admin_bar_items][enabled]" value="1" role="switch" aria-describedby="hab-admin-bar-items-enabled-desc" <?php checked( $hab_values['enabled'] ); ?>>
	</div>

	<div class="hab-module-body">
		<div class="hab-setting hab-setting-stacked">
			<p class="hab-setting-desc"><?php esc_html_e( 'Tick an item in a role’s column to remove it for that role. Removing the account menu also removes its Log out link.', 'hide-admin-bar-based-on-user-roles' ); ?></p>
			<?php
			$hab_panel_section = 'admin_bar_items';
			$hab_panel_items   = hab_Admin_Items::admin_bar_items();
			$hab_panel_hidden  = $hab_values['hidden'];
			$hab_grid_label    = __( 'Admin bar item', 'hide-admin-bar-based-on-user-roles' );
			require __DIR__ . '/role-grid.php';
			?>
		</div>
	</div>
</section>
