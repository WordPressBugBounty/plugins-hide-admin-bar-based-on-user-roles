<?php
/**
 * Strict mode settings.
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

$hab_values = $settings['strict_mode'];
?>
<section class="hab-card hab-module" aria-labelledby="hab-strict-mode-title">
	<div class="hab-card-header">
		<h2 id="hab-strict-mode-title"><?php esc_html_e( 'Strict mode', 'hide-admin-bar-based-on-user-roles' ); ?></h2>
		<p><?php esc_html_e( 'Some plugins, such as BuddyPress, wpForo or WC Vendors, turn the admin bar back on after this plugin hides it.', 'hide-admin-bar-based-on-user-roles' ); ?></p>
	</div>

	<div class="hab-setting">
		<div class="hab-setting-text">
			<label class="hab-setting-title" for="hab-strict-mode-enabled"><?php esc_html_e( 'Keep the admin bar hidden', 'hide-admin-bar-based-on-user-roles' ); ?></label>
			<p class="hab-setting-desc" id="hab-strict-mode-enabled-desc">
				<?php
				printf(
					/* translators: %s: link to the Admin bar tab. */
					esc_html__( 'Applies the rules from the %s tab once more, after every other plugin, so they always win. It only affects users those rules already cover.', 'hide-admin-bar-based-on-user-roles' ),
					'<a href="' . esc_url( $main_page ) . '">' . esc_html__( 'Admin bar', 'hide-admin-bar-based-on-user-roles' ) . '</a>'
				);
				?>
			</p>
		</div>
		<input type="checkbox" class="hab-switch hab-module-toggle" id="hab-strict-mode-enabled" name="hab_modules[strict_mode][enabled]" value="1" role="switch" aria-describedby="hab-strict-mode-enabled-desc" <?php checked( $hab_values['enabled'] ); ?>>
	</div>
</section>
