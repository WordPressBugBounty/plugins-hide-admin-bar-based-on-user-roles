<?php

/**
 * Settings page markup.
 *
 * Rendered by hab_Hide_Admin_Bar_Based_On_User_Roles_Admin::hide_admin_bar_settings(), which sets
 * $hide_for_all, $hide_for_guests, $roles, $selected_roles, $capabilities and $reset_url.
 *
 * @link       https://wpankit.com/
 * @since      1.7.0
 *
 * @package    Hide_Admin_Bar_Based_On_User_Roles
 * @subpackage Hide_Admin_Bar_Based_On_User_Roles/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wrap hab-settings">
	<div class="hab-header">
		<img class="hab-header-icon" src="<?php echo esc_url( plugin_dir_url( __DIR__ ) . 'images/hab-icon.svg' ); ?>" width="48" height="48" alt="">
		<div class="hab-header-text">
			<h1><?php esc_html_e( 'Hide Admin Bar', 'hide-admin-bar-based-on-user-roles' ); ?></h1>
			<p><?php esc_html_e( 'Choose who sees the WordPress admin bar on the front end of your site.', 'hide-admin-bar-based-on-user-roles' ); ?></p>
		</div>
		<div class="hab-header-links">
			<span class="hab-version">
				<?php
				/* translators: %s: plugin version number. */
				printf( esc_html__( 'Version %s', 'hide-admin-bar-based-on-user-roles' ), esc_html( HIDE_ADMIN_BAR_BASED_ON_USER_ROLES ) );
				?>
			</span>
			<a href="https://wordpress.org/support/plugin/hide-admin-bar-based-on-user-roles/" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Get support', 'hide-admin-bar-based-on-user-roles' ); ?></a>
			<a href="https://wordpress.org/support/plugin/hide-admin-bar-based-on-user-roles/reviews/#new-post" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Leave a review', 'hide-admin-bar-based-on-user-roles' ); ?></a>
		</div>
	</div>
	<hr class="wp-header-end">

	<?php include __DIR__ . '/review-banner.php'; ?>

	<div class="hab-layout">
		<div class="hab-main">
			<form id="hab-settings-form" class="hab-card" novalidate>
				<div class="hab-card-header">
					<h2><?php esc_html_e( 'Who shouldn’t see the admin bar?', 'hide-admin-bar-based-on-user-roles' ); ?></h2>
					<p><?php esc_html_e( 'These rules apply to the front end of your site. Inside the dashboard, WordPress always shows the admin bar.', 'hide-admin-bar-based-on-user-roles' ); ?></p>
				</div>

				<div class="hab-setting">
					<div class="hab-setting-text">
						<label class="hab-setting-title" for="hab-hide-for-all"><?php esc_html_e( 'Hide for everyone', 'hide-admin-bar-based-on-user-roles' ); ?></label>
						<p class="hab-setting-desc" id="hab-hide-for-all-desc"><?php esc_html_e( 'Remove the admin bar for every logged-in user, administrators included. Saving with this on clears the rules below.', 'hide-admin-bar-based-on-user-roles' ); ?></p>
					</div>
					<input type="checkbox" class="hab-switch" id="hab-hide-for-all" role="switch" aria-describedby="hab-hide-for-all-desc" <?php checked( $hide_for_all ); ?>>
				</div>

				<fieldset class="hab-rules" id="hab-rules" <?php disabled( $hide_for_all ); ?>>
					<legend class="screen-reader-text"><?php esc_html_e( 'Rules for specific visitors, roles and capabilities', 'hide-admin-bar-based-on-user-roles' ); ?></legend>
					<p class="hab-rules-note"><?php esc_html_e( 'Turn off “Hide for everyone” to use these rules.', 'hide-admin-bar-based-on-user-roles' ); ?></p>

					<div class="hab-setting">
						<div class="hab-setting-text">
							<label class="hab-setting-title" for="hab-hide-for-guests"><?php esc_html_e( 'Hide for logged-out visitors', 'hide-admin-bar-based-on-user-roles' ); ?></label>
							<p class="hab-setting-desc" id="hab-hide-for-guests-desc"><?php esc_html_e( 'WordPress doesn’t show the admin bar to logged-out visitors, but some plugins, such as BuddyPress, can. This makes sure they never see it.', 'hide-admin-bar-based-on-user-roles' ); ?></p>
						</div>
						<input type="checkbox" class="hab-switch" id="hab-hide-for-guests" role="switch" aria-describedby="hab-hide-for-guests-desc" <?php checked( $hide_for_guests ); ?>>
					</div>

					<div class="hab-setting hab-setting-stacked">
						<fieldset class="hab-fieldset">
							<legend class="hab-setting-title"><?php esc_html_e( 'Hide for these roles', 'hide-admin-bar-based-on-user-roles' ); ?></legend>
							<p class="hab-setting-desc"><?php esc_html_e( 'Users with any of the selected roles won’t see the admin bar.', 'hide-admin-bar-based-on-user-roles' ); ?></p>
							<div class="hab-roles">
								<?php foreach ( $roles as $role_key => $role_name ) : ?>
									<label class="hab-role">
										<input type="checkbox" name="hab_roles[]" value="<?php echo esc_attr( $role_key ); ?>" <?php checked( in_array( $role_key, $selected_roles, true ) ); ?>>
										<span><?php echo esc_html( translate_user_role( $role_name ) ); ?></span>
									</label>
								<?php endforeach; ?>
							</div>
						</fieldset>
					</div>

					<div class="hab-setting hab-setting-stacked">
						<label class="hab-setting-title" for="hab-capability-input"><?php esc_html_e( 'Hide for users with these capabilities', 'hide-admin-bar-based-on-user-roles' ); ?></label>
						<p class="hab-setting-desc" id="hab-capabilities-desc">
							<?php
							/* translators: %s: example capability name, such as edit_posts. */
							printf( esc_html__( 'Users who have any of these capabilities won’t see the admin bar. Type a capability, such as %s, and press Enter.', 'hide-admin-bar-based-on-user-roles' ), '<code>edit_posts</code>' );
							?>
						</p>
						<div class="hab-tags">
							<ul class="hab-tags-list">
								<?php
								foreach ( $capabilities as $capability ) :
									/* translators: %s: capability name. */
									$remove_label = sprintf( __( 'Remove %s', 'hide-admin-bar-based-on-user-roles' ), $capability );
									?>
									<li class="hab-tag">
										<span class="hab-tag-label"><?php echo esc_html( $capability ); ?></span>
										<button type="button" class="hab-tag-remove" aria-label="<?php echo esc_attr( $remove_label ); ?>"><span aria-hidden="true">&times;</span></button>
									</li>
								<?php endforeach; ?>
							</ul>
							<input type="text" id="hab-capability-input" class="hab-tags-input" autocomplete="off" spellcheck="false" aria-describedby="hab-capabilities-desc" placeholder="<?php esc_attr_e( 'Add a capability', 'hide-admin-bar-based-on-user-roles' ); ?>">
						</div>
					</div>
				</fieldset>

				<div class="hab-card-footer">
					<button type="submit" class="button button-primary" id="hab-save"><?php esc_html_e( 'Save changes', 'hide-admin-bar-based-on-user-roles' ); ?></button>
					<span class="spinner"></span>
					<span class="hab-status" id="hab-status" role="status" aria-live="polite"></span>
				</div>
			</form>

			<?php if ( $reset_url ) : ?>
				<details class="hab-card hab-reset">
					<summary><?php esc_html_e( 'Reset settings', 'hide-admin-bar-based-on-user-roles' ); ?></summary>
					<div class="hab-reset-body">
						<p><?php esc_html_e( 'Clear every rule and go back to the defaults. The admin bar will show again for everyone.', 'hide-admin-bar-based-on-user-roles' ); ?></p>
						<a class="button" id="hab-reset" href="<?php echo esc_url( $reset_url ); ?>"><?php esc_html_e( 'Reset all settings', 'hide-admin-bar-based-on-user-roles' ); ?></a>
					</div>
				</details>
			<?php endif; ?>
		</div>

		<aside class="hab-sidebar">
			<?php include __DIR__ . '/other-plugins.php'; ?>
		</aside>
	</div>
</div>
