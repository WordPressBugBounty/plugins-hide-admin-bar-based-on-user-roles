<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Check if the review banner should be hidden
$hide_review_banner = get_user_meta(get_current_user_id(), 'hab_hide_review_banner', true);
$hide_review_until = get_user_meta(get_current_user_id(), 'hab_hide_review_until', true);

// Get the plugin installation date
$plugin_install_date = get_option('hab_plugin_install_date');
if (!$plugin_install_date) {
    // If no install date is set, set it to now
    $plugin_install_date = time();
    update_option('hab_plugin_install_date', $plugin_install_date);
}

// Calculate days since installation
$days_since_install = floor((time() - $plugin_install_date) / DAY_IN_SECONDS);

// Show banner if:
// 1. Not permanently hidden
// 2. Not temporarily hidden or temporary period has expired
// 3. At least 7 days have passed since installation
if ($hide_review_banner !== 'permanent' &&
    (empty($hide_review_until) || time() > $hide_review_until) &&
    $days_since_install >= 7) :
?>
<div class="hab-review" id="hab-review">
    <span class="dashicons dashicons-star-filled" aria-hidden="true"></span>
    <div class="hab-review-text">
        <p class="hab-review-title"><?php esc_html_e( 'Enjoying Hide Admin Bar?', 'hide-admin-bar-based-on-user-roles' ); ?></p>
        <p><?php esc_html_e( 'A quick review on WordPress.org helps other site owners find it.', 'hide-admin-bar-based-on-user-roles' ); ?></p>
    </div>
    <div class="hab-review-actions">
        <a class="button button-primary" href="https://wordpress.org/support/plugin/hide-admin-bar-based-on-user-roles/reviews/#new-post" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Leave a review', 'hide-admin-bar-based-on-user-roles' ); ?></a>
        <button type="button" class="button-link hab-review-dismiss" data-dismiss="30days"><?php esc_html_e( 'Maybe later', 'hide-admin-bar-based-on-user-roles' ); ?></button>
        <button type="button" class="button-link hab-review-dismiss" data-dismiss="permanent"><?php esc_html_e( 'Don’t show again', 'hide-admin-bar-based-on-user-roles' ); ?></button>
    </div>
</div>
<?php endif; ?>
