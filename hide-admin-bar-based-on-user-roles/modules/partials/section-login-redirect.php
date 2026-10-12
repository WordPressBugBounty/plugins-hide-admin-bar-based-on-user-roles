<?php
/**
 * Login redirect settings.
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

$hab_values = $settings['login_redirect'];
?>
<section class="hab-card hab-module" aria-labelledby="hab-login-redirect-title">
	<div class="hab-card-header">
		<h2 id="hab-login-redirect-title"><?php esc_html_e( 'Login redirect', 'hide-admin-bar-based-on-user-roles' ); ?></h2>
		<p><?php esc_html_e( 'Choose where each role lands after logging in, instead of the dashboard.', 'hide-admin-bar-based-on-user-roles' ); ?></p>
	</div>

	<div class="hab-setting">
		<div class="hab-setting-text">
			<label class="hab-setting-title" for="hab-login-redirect-enabled"><?php esc_html_e( 'Redirect after login', 'hide-admin-bar-based-on-user-roles' ); ?></label>
			<p class="hab-setting-desc" id="hab-login-redirect-enabled-desc"><?php esc_html_e( 'Works with the WordPress login form. Login links that ask for a specific page, such as “Log in to comment”, still go to that page.', 'hide-admin-bar-based-on-user-roles' ); ?></p>
		</div>
		<input type="checkbox" class="hab-switch hab-module-toggle" id="hab-login-redirect-enabled" name="hab_modules[login_redirect][enabled]" value="1" role="switch" aria-describedby="hab-login-redirect-enabled-desc" <?php checked( $hab_values['enabled'] ); ?>>
	</div>

	<div class="hab-module-body">
		<div class="hab-setting hab-setting-stacked">
			<fieldset class="hab-fieldset">
				<legend class="hab-setting-title"><?php esc_html_e( 'Page for each role', 'hide-admin-bar-based-on-user-roles' ); ?></legend>
				<p class="hab-setting-desc">
					<?php
					printf(
						/* translators: %s: example path, such as /my-account/. */
						esc_html__( 'Enter a page on this site, such as %s. Leave a role empty to keep the WordPress default.', 'hide-admin-bar-based-on-user-roles' ),
						'<code>/my-account/</code>'
					);
					?>
				</p>
				<div class="hab-redirects">
					<?php foreach ( $roles as $hab_role_key => $hab_role_name ) : ?>
						<?php $hab_field_id = 'hab-login-redirect-' . sanitize_html_class( $hab_role_key ); ?>
						<div class="hab-redirect-row">
							<label for="<?php echo esc_attr( $hab_field_id ); ?>"><?php echo esc_html( translate_user_role( $hab_role_name ) ); ?></label>
							<input type="text" class="regular-text" id="<?php echo esc_attr( $hab_field_id ); ?>" name="hab_modules[login_redirect][urls][<?php echo esc_attr( $hab_role_key ); ?>]" value="<?php echo esc_attr( isset( $hab_values['urls'][ $hab_role_key ] ) ? $hab_values['urls'][ $hab_role_key ] : '' ); ?>" placeholder="<?php esc_attr_e( 'WordPress default', 'hide-admin-bar-based-on-user-roles' ); ?>" autocomplete="off" spellcheck="false">
						</div>
					<?php endforeach; ?>
				</div>
			</fieldset>
		</div>
	</div>
</section>
