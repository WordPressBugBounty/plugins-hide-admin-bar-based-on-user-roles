<?php
/**
 * Dashboard access settings.
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

$hab_values = $settings['dashboard_access'];
?>
<section class="hab-card hab-module" aria-labelledby="hab-dashboard-access-title">
	<div class="hab-card-header">
		<h2 id="hab-dashboard-access-title"><?php esc_html_e( 'Dashboard access', 'hide-admin-bar-based-on-user-roles' ); ?></h2>
		<p><?php esc_html_e( 'Keep selected roles out of wp-admin. When they try to open it, they’re sent to the page you choose.', 'hide-admin-bar-based-on-user-roles' ); ?></p>
	</div>

	<div class="hab-setting">
		<div class="hab-setting-text">
			<label class="hab-setting-title" for="hab-dashboard-access-enabled"><?php esc_html_e( 'Block the dashboard', 'hide-admin-bar-based-on-user-roles' ); ?></label>
			<p class="hab-setting-desc" id="hab-dashboard-access-enabled-desc"><?php esc_html_e( 'Administrators always keep access. Background requests that themes and plugins rely on keep working.', 'hide-admin-bar-based-on-user-roles' ); ?></p>
		</div>
		<input type="checkbox" class="hab-switch hab-module-toggle" id="hab-dashboard-access-enabled" name="hab_modules[dashboard_access][enabled]" value="1" role="switch" aria-describedby="hab-dashboard-access-enabled-desc" <?php checked( $hab_values['enabled'] ); ?>>
	</div>

	<div class="hab-module-body">
		<div class="hab-setting hab-setting-stacked">
			<fieldset class="hab-fieldset">
				<legend class="hab-setting-title"><?php esc_html_e( 'Block these roles', 'hide-admin-bar-based-on-user-roles' ); ?></legend>
				<p class="hab-setting-desc"><?php esc_html_e( 'Users with any of these roles can’t open the dashboard. Roles that can manage the site aren’t listed.', 'hide-admin-bar-based-on-user-roles' ); ?></p>
				<div class="hab-roles">
					<?php foreach ( $roles as $hab_role_key => $hab_role_name ) : ?>
						<?php
						if ( in_array( $hab_role_key, $admin_roles, true ) ) {
							continue;
						}
						?>
						<label class="hab-role">
							<input type="checkbox" name="hab_modules[dashboard_access][roles][]" value="<?php echo esc_attr( $hab_role_key ); ?>" <?php checked( in_array( $hab_role_key, $hab_values['roles'], true ) ); ?>>
							<span><?php echo esc_html( translate_user_role( $hab_role_name ) ); ?></span>
						</label>
					<?php endforeach; ?>
				</div>
			</fieldset>
		</div>

		<div class="hab-setting hab-setting-stacked">
			<label class="hab-setting-title" for="hab-dashboard-access-redirect"><?php esc_html_e( 'Send them to', 'hide-admin-bar-based-on-user-roles' ); ?></label>
			<p class="hab-setting-desc" id="hab-dashboard-access-redirect-desc"><?php esc_html_e( 'A page on this site. Leave it empty to use your home page.', 'hide-admin-bar-based-on-user-roles' ); ?></p>
			<input type="text" class="regular-text hab-url-input" id="hab-dashboard-access-redirect" name="hab_modules[dashboard_access][redirect_url]" value="<?php echo esc_attr( $hab_values['redirect_url'] ); ?>" placeholder="<?php echo esc_attr( home_url( '/' ) ); ?>" aria-describedby="hab-dashboard-access-redirect-desc" autocomplete="off" spellcheck="false">
		</div>

		<div class="hab-setting">
			<div class="hab-setting-text">
				<label class="hab-setting-title" for="hab-dashboard-access-profile"><?php esc_html_e( 'Let them edit their profile', 'hide-admin-bar-based-on-user-roles' ); ?></label>
				<p class="hab-setting-desc" id="hab-dashboard-access-profile-desc"><?php esc_html_e( 'Keeps the Profile screen open so they can change their name, email address or password.', 'hide-admin-bar-based-on-user-roles' ); ?></p>
			</div>
			<input type="checkbox" class="hab-switch" id="hab-dashboard-access-profile" name="hab_modules[dashboard_access][allow_profile]" value="1" role="switch" aria-describedby="hab-dashboard-access-profile-desc" <?php checked( $hab_values['allow_profile'] ); ?>>
		</div>

		<p class="hab-module-tip">
			<?php
			printf(
				/* translators: %s: link to the Admin bar tab. */
				esc_html__( 'Blocked users still see the admin bar on your site unless you hide it for them on the %s tab.', 'hide-admin-bar-based-on-user-roles' ),
				'<a href="' . esc_url( $main_page ) . '">' . esc_html__( 'Admin bar', 'hide-admin-bar-based-on-user-roles' ) . '</a>'
			);
			?>
		</p>
	</div>
</section>
