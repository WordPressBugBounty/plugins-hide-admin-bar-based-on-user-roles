<?php
/**
 * Settings for the admin bar inside the dashboard.
 *
 * @link       https://wpankit.com/
 * @since      7.4.0
 *
 * @package    hab_Hide_Admin_Bar_Based_On_User_Roles
 * @subpackage hab_Hide_Admin_Bar_Based_On_User_Roles/modules/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$hab_values = $settings['dashboard_admin_bar'];
?>
<section class="hab-card hab-module" aria-labelledby="hab-dashboard-admin-bar-title">
	<div class="hab-card-header">
		<h2 id="hab-dashboard-admin-bar-title"><?php esc_html_e( 'Admin bar in the dashboard', 'hide-admin-bar-based-on-user-roles' ); ?></h2>
		<p><?php esc_html_e( 'WordPress always shows the admin bar inside wp-admin. Hide it there for the roles you pick.', 'hide-admin-bar-based-on-user-roles' ); ?></p>
	</div>

	<div class="hab-setting">
		<div class="hab-setting-text">
			<label class="hab-setting-title" for="hab-dashboard-admin-bar-enabled"><?php esc_html_e( 'Hide it in the dashboard', 'hide-admin-bar-based-on-user-roles' ); ?></label>
			<p class="hab-setting-desc" id="hab-dashboard-admin-bar-enabled-desc"><?php esc_html_e( 'On phones and small tablets the admin bar stays, because it holds the button that opens the WordPress menu.', 'hide-admin-bar-based-on-user-roles' ); ?></p>
		</div>
		<input type="checkbox" class="hab-switch hab-module-toggle" id="hab-dashboard-admin-bar-enabled" name="hab_modules[dashboard_admin_bar][enabled]" value="1" role="switch" aria-describedby="hab-dashboard-admin-bar-enabled-desc" <?php checked( $hab_values['enabled'] ); ?>>
	</div>

	<div class="hab-module-body">
		<div class="hab-setting hab-setting-stacked">
			<fieldset class="hab-fieldset">
				<legend class="hab-setting-title"><?php esc_html_e( 'For these roles', 'hide-admin-bar-based-on-user-roles' ); ?></legend>
				<p class="hab-setting-desc"><?php esc_html_e( 'Users with any of these roles won’t see the admin bar inside the dashboard.', 'hide-admin-bar-based-on-user-roles' ); ?></p>
				<div class="hab-roles">
					<?php foreach ( $roles as $hab_role_key => $hab_role_name ) : ?>
						<label class="hab-role">
							<input type="checkbox" name="hab_modules[dashboard_admin_bar][roles][]" value="<?php echo esc_attr( $hab_role_key ); ?>" <?php checked( in_array( $hab_role_key, $hab_values['roles'], true ) ); ?>>
							<span><?php echo esc_html( translate_user_role( $hab_role_name ) ); ?></span>
						</label>
					<?php endforeach; ?>
				</div>
			</fieldset>
		</div>
	</div>
</section>
