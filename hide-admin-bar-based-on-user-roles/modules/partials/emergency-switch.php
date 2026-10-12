<?php
/**
 * How to switch the modules off from wp-config.php.
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
<section class="hab-card" aria-labelledby="hab-emergency-title">
	<div class="hab-card-header">
		<h2 id="hab-emergency-title"><?php esc_html_e( 'If something goes wrong', 'hide-admin-bar-based-on-user-roles' ); ?></h2>
		<p><?php esc_html_e( 'If a rule on the Dashboard & login, Admin cleanup or Compatibility tab locks someone out, add this line to wp-config.php. It switches those features off and keeps your settings. The rules on the Admin bar tab keep working.', 'hide-admin-bar-based-on-user-roles' ); ?></p>
	</div>
	<div class="hab-card-body">
		<code class="hab-code">define( 'HAB_DISABLE_MODULES', true );</code>
	</div>
</section>
