<?php
/**
 * Switch to user settings.
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

$hab_values = $settings['switch_user'];
?>
<section class="hab-card hab-module" aria-labelledby="hab-switch-user-title">
	<div class="hab-card-header">
		<h2 id="hab-switch-user-title"><?php esc_html_e( 'Switch to user', 'hide-admin-bar-based-on-user-roles' ); ?></h2>
		<p><?php esc_html_e( 'See your site exactly as one of your users does, to check your settings, then switch back in one click.', 'hide-admin-bar-based-on-user-roles' ); ?></p>
	</div>

	<div class="hab-setting">
		<div class="hab-setting-text">
			<label class="hab-setting-title" for="hab-switch-user-enabled"><?php esc_html_e( 'Allow switching to users', 'hide-admin-bar-based-on-user-roles' ); ?></label>
			<p class="hab-setting-desc" id="hab-switch-user-enabled-desc"><?php esc_html_e( 'Only administrators can switch, and only to users who can’t manage the site. While you’re switched, a bar at the bottom of the screen shows who you’re viewing as.', 'hide-admin-bar-based-on-user-roles' ); ?></p>
		</div>
		<input type="checkbox" class="hab-switch hab-module-toggle" id="hab-switch-user-enabled" name="hab_modules[switch_user][enabled]" value="1" role="switch" aria-describedby="hab-switch-user-enabled-desc" <?php checked( $hab_values['enabled'] ); ?>>
	</div>

	<div class="hab-module-body">
		<p class="hab-module-tip">
			<?php
			printf(
				/* translators: %s: link to the Users screen. */
				esc_html__( 'Once it’s on, use the “Switch to” button next to a user on the %s screen.', 'hide-admin-bar-based-on-user-roles' ),
				'<a href="' . esc_url( admin_url( 'users.php' ) ) . '">' . esc_html__( 'Users', 'hide-admin-bar-based-on-user-roles' ) . '</a>'
			);
			?>
		</p>
	</div>
</section>
