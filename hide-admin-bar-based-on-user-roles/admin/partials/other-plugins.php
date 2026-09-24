<?php
/**
 * Settings page sidebar that introduces our other plugins.
 *
 * @package    hab_Hide_Admin_Bar_Based_On_User_Roles
 * @subpackage hab_Hide_Admin_Bar_Based_On_User_Roles/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Links carry no tracking parameters: wp.org guideline 11 does not allow tracking referrals from admin ads.
$hab_featured_plugins = array(
    array(
        'name'        => 'Page Visit Counter',
        'tagline'     => __( 'Privacy-first analytics inside WordPress', 'hide-admin-bar-based-on-user-roles' ),
        'description' => __( 'See visitors and page views right in your dashboard, with no cookies and no external scripts.', 'hide-admin-bar-based-on-user-roles' ),
        'url'         => 'https://pagevisitcounter.com/',
        'icon'        => 'page-visit-counter-icon.svg',
    ),
    array(
        'name'        => 'PushRow for Google Sheets',
        'tagline'     => __( 'Keep Google Sheets in sync with WordPress', 'hide-admin-bar-based-on-user-roles' ),
        'description' => __( 'Send posts, users, form entries and WooCommerce orders to any spreadsheet.', 'hide-admin-bar-based-on-user-roles' ),
        'url'         => 'https://getpushrow.com/',
        'icon'        => 'pushrow-icon.svg',
    ),
);

$hab_wporg_plugins = array(
    array(
        'name'    => 'UltimaKit',
        'tagline' => __( 'Admin tools, security and performance in one plugin', 'hide-admin-bar-based-on-user-roles' ),
        'url'     => 'https://wordpress.org/plugins/ultimakit-for-wp/',
        'icon'    => 'ultimakit-icon.png',
    ),
    array(
        'name'    => 'Disable Block Editor FullScreen mode',
        'tagline' => __( 'Open the block editor without fullscreen mode', 'hide-admin-bar-based-on-user-roles' ),
        'url'     => 'https://wordpress.org/plugins/disable-block-editor-fullscreen-mode/',
        'icon'    => 'disable-block-editor-fullscreen-mode-icon.png',
    ),
);
?>
<div class="hab-card hab-promo">
    <h2 class="hab-promo-title"><?php esc_html_e( 'More from the makers of Hide Admin Bar', 'hide-admin-bar-based-on-user-roles' ); ?></h2>

    <?php foreach ( $hab_featured_plugins as $hab_plugin ) : ?>
        <a class="hab-promo-featured" href="<?php echo esc_url( $hab_plugin['url'] ); ?>" target="_blank" rel="noopener noreferrer">
            <span class="hab-promo-head">
                <img src="<?php echo esc_url( plugin_dir_url( __DIR__ ) . 'images/' . $hab_plugin['icon'] ); ?>" width="40" height="40" alt="">
                <span>
                    <span class="hab-promo-name"><?php echo esc_html( $hab_plugin['name'] ); ?></span>
                    <span class="hab-promo-tagline"><?php echo esc_html( $hab_plugin['tagline'] ); ?></span>
                </span>
            </span>
            <span class="hab-promo-description"><?php echo esc_html( $hab_plugin['description'] ); ?></span>
            <span class="hab-promo-cta">
                <?php esc_html_e( 'Learn more', 'hide-admin-bar-based-on-user-roles' ); ?> <span aria-hidden="true">&rarr;</span>
                <span class="screen-reader-text"><?php esc_html_e( '(opens in a new tab)', 'hide-admin-bar-based-on-user-roles' ); ?></span>
            </span>
        </a>
    <?php endforeach; ?>

    <h3 class="hab-promo-subtitle"><?php esc_html_e( 'More free plugins on WordPress.org', 'hide-admin-bar-based-on-user-roles' ); ?></h3>
    <ul class="hab-promo-list">
        <?php foreach ( $hab_wporg_plugins as $hab_plugin ) : ?>
            <li>
                <a class="hab-promo-item" href="<?php echo esc_url( $hab_plugin['url'] ); ?>" target="_blank" rel="noopener noreferrer">
                    <img src="<?php echo esc_url( plugin_dir_url( __DIR__ ) . 'images/' . $hab_plugin['icon'] ); ?>" width="32" height="32" alt="">
                    <span>
                        <span class="hab-promo-name"><?php echo esc_html( $hab_plugin['name'] ); ?></span>
                        <span class="hab-promo-tagline"><?php echo esc_html( $hab_plugin['tagline'] ); ?></span>
                        <span class="screen-reader-text"><?php esc_html_e( '(opens in a new tab)', 'hide-admin-bar-based-on-user-roles' ); ?></span>
                    </span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
