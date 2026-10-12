<?php
/**
 * Module settings page markup.
 *
 * Rendered by hab_Modules_Admin::render_page(), which sets $page, $sections, $settings, $roles,
 * $admin_roles, $main_page and $switched_off.
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
?>
<div class="wrap hab-settings">
	<div class="hab-header">
		<img class="hab-header-icon" src="<?php echo esc_url( plugin_dir_url( dirname( __DIR__ ) ) . 'admin/images/hab-icon.svg' ); ?>" width="48" height="48" alt="">
		<div class="hab-header-text">
			<h1><?php esc_html_e( 'Hide Admin Bar', 'hide-admin-bar-based-on-user-roles' ); ?></h1>
			<p><?php esc_html_e( 'Choose who sees the admin bar, who can open the dashboard, and where users land after logging in.', 'hide-admin-bar-based-on-user-roles' ); ?></p>
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

	<?php $this->render_tabs( $page ); ?>

	<?php if ( $switched_off ) : ?>
		<div class="notice notice-warning inline hab-switched-off">
			<p>
				<?php
				printf(
					/* translators: %s: name of the PHP constant. */
					esc_html__( '%s is set in wp-config.php, so everything on this tab is switched off. Your settings are kept.', 'hide-admin-bar-based-on-user-roles' ),
					'<code>HAB_DISABLE_MODULES</code>'
				);
				?>
			</p>
		</div>
	<?php endif; ?>

	<div class="hab-layout">
		<div class="hab-main">
			<form method="post" action="options.php" class="hab-modules-form">
				<?php settings_fields( 'hab_modules' ); ?>

				<?php foreach ( $sections as $section ) : ?>
					<input type="hidden" name="hab_modules[sections][]" value="<?php echo esc_attr( $section ); ?>">
					<?php include __DIR__ . '/section-' . str_replace( '_', '-', $section ) . '.php'; ?>
				<?php endforeach; ?>

				<div class="hab-savebar">
					<?php submit_button( __( 'Save changes', 'hide-admin-bar-based-on-user-roles' ), 'primary', 'submit', false ); ?>
				</div>
			</form>

			<?php if ( 'hide-admin-bar-compatibility' === $page ) : ?>
				<?php include __DIR__ . '/emergency-switch.php'; ?>
			<?php endif; ?>
		</div>

		<aside class="hab-sidebar">
			<?php require dirname( dirname( __DIR__ ) ) . '/admin/partials/other-plugins.php'; ?>
		</aside>
	</div>
</div>
