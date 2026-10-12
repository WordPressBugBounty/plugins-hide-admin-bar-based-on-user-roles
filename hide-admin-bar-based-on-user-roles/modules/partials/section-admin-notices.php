<?php
/**
 * Admin notices settings.
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

$hab_values = $settings['admin_notices'];
?>
<section class="hab-card hab-module" aria-labelledby="hab-admin-notices-title">
	<div class="hab-card-header">
		<h2 id="hab-admin-notices-title"><?php esc_html_e( 'Admin notices', 'hide-admin-bar-based-on-user-roles' ); ?></h2>
		<p><?php esc_html_e( 'Hide the notices that WordPress, themes and plugins show at the top of dashboard screens.', 'hide-admin-bar-based-on-user-roles' ); ?></p>
	</div>

	<div class="hab-setting">
		<div class="hab-setting-text">
			<label class="hab-setting-title" for="hab-admin-notices-enabled"><?php esc_html_e( 'Hide admin notices', 'hide-admin-bar-based-on-user-roles' ); ?></label>
			<p class="hab-setting-desc" id="hab-admin-notices-enabled-desc"><?php esc_html_e( 'This hides every notice for these roles, including update reminders and messages such as “Settings saved” that some plugins show as notices.', 'hide-admin-bar-based-on-user-roles' ); ?></p>
		</div>
		<input type="checkbox" class="hab-switch hab-module-toggle" id="hab-admin-notices-enabled" name="hab_modules[admin_notices][enabled]" value="1" role="switch" aria-describedby="hab-admin-notices-enabled-desc" <?php checked( $hab_values['enabled'] ); ?>>
	</div>

	<div class="hab-module-body">
		<div class="hab-setting hab-setting-stacked">
			<fieldset class="hab-fieldset">
				<legend class="hab-setting-title"><?php esc_html_e( 'For these roles', 'hide-admin-bar-based-on-user-roles' ); ?></legend>
				<p class="hab-setting-desc"><?php esc_html_e( 'Users with any of these roles won’t see admin notices.', 'hide-admin-bar-based-on-user-roles' ); ?></p>
				<div class="hab-roles">
					<?php foreach ( $roles as $hab_role_key => $hab_role_name ) : ?>
						<label class="hab-role">
							<input type="checkbox" name="hab_modules[admin_notices][roles][]" value="<?php echo esc_attr( $hab_role_key ); ?>" <?php checked( in_array( $hab_role_key, $hab_values['roles'], true ) ); ?>>
							<span><?php echo esc_html( translate_user_role( $hab_role_name ) ); ?></span>
						</label>
					<?php endforeach; ?>
				</div>
			</fieldset>
		</div>
	</div>
</section>
