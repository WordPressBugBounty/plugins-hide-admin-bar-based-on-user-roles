<?php

/**
 * The plugin bootstrap file
 *
 * @link              https://wpankit.com/
 * @since             1.0.0
 * @package           Hide_Admin_Bar_Based_On_User_Roles
 *
 *
 * @wordpress-plugin
 * Plugin Name:       Hide Admin Bar Based on User Roles
 * Plugin URI:        https://wordpress.org/plugins/hide-admin-bar-based-on-user-roles/
 * Description:       Hide the WordPress Admin Bar for specific user roles, capabilities, or guests. Lightweight and works out of the box.
 * Version:           7.3.0
 * Author:            WPAnkit
 * Author URI:        https://wpankit.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       hide-admin-bar-based-on-user-roles
 * Domain Path:       /languages
 */
/*
Hide Admin Bar Based on User Roles is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
Hide Admin Bar Based on User Roles is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with Hide Admin Bar Based on User Roles. If not, see {URI to Plugin License}.
*/
// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}
/**
 * Currently plugin version.
 * Start at version 1.7.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
// Use the existing version constant if the free plugin defined it first.
if ( !defined( 'HIDE_ADMIN_BAR_BASED_ON_USER_ROLES' ) ) {
    define( 'HIDE_ADMIN_BAR_BASED_ON_USER_ROLES', '7.3.0' );
}
if ( !function_exists( 'habbourp_fs' ) ) {
    // Create a helper function for easy SDK access.
    function habbourp_fs() {
        global $habbourp_fs;
        if ( !isset( $habbourp_fs ) ) {
            // Activate multisite network integration.
            if ( !defined( 'WP_FS__PRODUCT_18739_MULTISITE' ) ) {
                define( 'WP_FS__PRODUCT_18739_MULTISITE', true );
            }
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/includes/freemius/start.php';
            $habbourp_fs = fs_dynamic_init( array(
                'id'               => '18739',
                'slug'             => 'hide-admin-bar-based-on-user-roles',
                'premium_slug'     => 'hide-admin-bar-based-on-user-roles-pro',
                'type'             => 'plugin',
                'public_key'       => 'pk_86e4f935219abb51f4bba4983e178',
                'is_premium'       => false,
                'premium_suffix'   => 'Pro',
                'has_addons'       => false,
                'has_paid_plans'   => false,
                'has_affiliation'  => false,
                'menu'             => array(
                    'slug'    => 'hide-admin-bar-settings',
                    'support' => false,
                    'contact' => false,
                    'pricing' => false,
                    'network' => true,
                    'parent'  => array(
                        'slug' => 'options-general.php',
                    ),
                ),
                'is_live'          => true,
                'is_org_compliant' => true,
            ) );
        }
        return $habbourp_fs;
    }

    // Init Freemius.
    habbourp_fs();
    // Signal that SDK was initiated.
    do_action( 'habbourp_fs_loaded' );
    // The plugin is completely free. Sites that opted in earlier can still have the old paid plans
    // cached by Freemius, so switch off every pricing, upgrade and trial prompt explicitly.
    habbourp_fs()->add_filter( 'is_pricing_page_visible', '__return_false' );
    habbourp_fs()->add_filter( 'has_paid_plan_account', '__return_false' );
    habbourp_fs()->add_filter( 'show_trial', '__return_false' );
    // Earlier versions could store a "Start free trial" notice, which Freemius keeps showing until it's
    // dismissed. Clear it before Freemius checks for it on admin_init.
    function hab_remove_trial_notice() {
        habbourp_fs()->remove_sticky( 'trial_promotion' );
    }

    add_action( 'admin_init', 'hab_remove_trial_notice', 5 );
    /**
     * The code that runs during plugin activation.
     * This action is documented in includes/class-hide-admin-bar-based-on-user-roles-activator.php
     */
    function hab_activate_hide_admin_bar_based_on_user_roles() {
        require_once plugin_dir_path( __FILE__ ) . 'includes/class-hide-admin-bar-based-on-user-roles-activator.php';
        hab_Hide_Admin_Bar_Based_On_User_Roles_Activator::activate();
    }

    /**
     * The code that runs during plugin deactivation.
     * This action is documented in includes/class-hide-admin-bar-based-on-user-roles-deactivator.php
     */
    function hab_deactivate_hide_admin_bar_based_on_user_roles() {
        require_once plugin_dir_path( __FILE__ ) . 'includes/class-hide-admin-bar-based-on-user-roles-deactivator.php';
        hab_Hide_Admin_Bar_Based_On_User_Roles_Deactivator::deactivate();
    }

    register_activation_hook( __FILE__, 'hab_activate_hide_admin_bar_based_on_user_roles' );
    register_deactivation_hook( __FILE__, 'hab_deactivate_hide_admin_bar_based_on_user_roles' );
    /**
     * The core plugin class that is used to define internationalization,
     * admin-specific hooks, and public-facing site hooks.
     */
    require plugin_dir_path( __FILE__ ) . 'includes/class-hide-admin-bar-based-on-user-roles.php';
    /**
     * Begins execution of the plugin.
     *
     * Since everything within the plugin is registered via hooks,
     * then kicking off the plugin from this point in the file does
     * not affect the page life cycle.
     *
     * @since    1.7.0
     */
    function hab_run_hide_admin_bar_based_on_user_roles() {
        $plugin = new hab_Hide_Admin_Bar_Based_On_User_Roles();
        $plugin->run();
    }

    add_action( 'plugins_loaded', 'hab_run_hide_admin_bar_based_on_user_roles' );
    // Not like register_uninstall_hook(), you do NOT have to use a static function.
    habbourp_fs()->add_action( 'after_uninstall', 'habbourp_fs_uninstall_cleanup' );
    function habbourp_fs_uninstall_cleanup() {
        // Delete individual site settings
        delete_option( 'hab_settings' );
        delete_option( 'hab_reset_key' );
    }

}
