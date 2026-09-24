=== Hide Admin Bar Based on User Roles – Disable the WordPress Toolbar by Role or Capability ===
Contributors: ankitmaru, siapanchal
Tags: hide admin bar, admin bar, toolbar, user roles, remove admin bar
Plugin URI: https://wordpress.org/plugins/hide-admin-bar-based-on-user-roles/
Author: WPAnkit
Author URI: https://wpankit.com/
Requires at least: 5.5
Tested up to: 7.1
Requires PHP: 5.6
Stable tag: 7.3.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Hide the WordPress admin bar for specific user roles or capabilities. Remove the toolbar for subscribers, customers & guests.

== Description ==

**Hide Admin Bar Based on User Roles** lets you choose who sees the WordPress admin bar (the toolbar) on the front end of your site.

Remove it for subscribers, hide it from WooCommerce customers, turn it off for everyone, or target specific roles and capabilities, all from one settings page and without writing code.

The plugin is lightweight and works as soon as you activate it. Trusted on **20,000+ active WordPress sites**.

It's completely free: no Pro version, no locked features, no upsells.

https://www.youtube.com/watch?v=25WBldgArAk

https://www.youtube.com/watch?v=_BAwxGVnKNY

> Simple but great plugin. 🙂
> - [wptoolsdev](https://wordpress.org/support/topic/simple-but-great-plugin-12/)

> Works flawlessly! 🙂
> - [thebrazeneye](https://wordpress.org/support/topic/works-flawlessly-129/)

= Why hide the WordPress admin bar? =

The black toolbar at the top of your site is useful for admins — but for everyone else it exposes backend links, breaks your design, and confuses non-technical users. Hiding it is essential for:

* **Membership sites** – give members a clean, frontend-only experience with no WordPress branding
* **WooCommerce stores** – stop showing the toolbar to customers after they log in
* **LMS & course platforms** – keep students focused on your content, not the WordPress UI
* **Client websites** – hand over polished sites where editors see only what they need
* **Communities & directories** – hide backend access hints from registered users
* **Anyone who wants a cleaner frontend** – remove the admin bar without touching functions.php

= Features =

* **Hide for everyone:** remove the admin bar from the front end for every logged-in user.
* **Hide for logged-out visitors:** make sure visitors never see it, even when a plugin such as BuddyPress shows it to them.
* **Hide by role:** pick roles such as Subscriber, Customer or Editor, including custom roles from other plugins.
* **Hide by capability:** hide it for anyone who has a capability, for example `edit_posts`.
* **Nothing added to your site's front end:** no CSS, no JavaScript, no extra database queries.

= Works with your setup =

Hide Admin Bar Based on User Roles works with any theme and any plugin that registers user roles — including WooCommerce (Customer role), membership plugins, LMS plugins, and custom roles. Fully compatible with Elementor, Divi, Beaver Builder, Bricks, and WordPress Multisite.

= More from the makers of Hide Admin Bar =

* **[Page Visit Counter](https://pagevisitcounter.com/)** – Privacy-first analytics inside WordPress. See visitors and page views in your dashboard, with no cookies and no external scripts.
* **[PushRow for Google Sheets](https://getpushrow.com/)** – Keep Google Sheets in sync with WordPress. Send posts, users, form entries and WooCommerce orders to any spreadsheet.
* **[UltimaKit](https://wordpress.org/plugins/ultimakit-for-wp/)** – Admin tools, security and performance in one plugin.
* **[Disable Block Editor FullScreen mode](https://wordpress.org/plugins/disable-block-editor-fullscreen-mode/)** – Open the block editor without fullscreen mode.
* **[NoteFlow](https://wordpress.org/plugins/noteflow/)** – Create, organize and manage notes in your WordPress dashboard.
* **[Like Dislike For WP](https://wordpress.org/plugins/like-dislike-for-wp/)** – Add like and dislike buttons to your posts and pages, with vote stats.

== Installation ==

1. In your dashboard, go to **Plugins → Add New Plugin** and search for *Hide Admin Bar Based on User Roles*. Or upload the plugin folder to `/wp-content/plugins/`.
2. Activate the plugin.
3. Go to **Settings → Hide Admin Bar Settings** and choose who shouldn't see the admin bar.

== Frequently Asked Questions ==

= How do I hide the admin bar for all users? =
Go to Settings → Hide Admin Bar Settings and turn on “Hide for everyone”. The admin bar disappears from the front end for every logged-in user, administrators included.

= How do I hide the admin bar for subscribers or WooCommerce customers only? =
Under “Hide for these roles”, select Subscriber or Customer and save. Administrators and any roles you leave unselected keep the admin bar. Custom roles from membership, LMS and other plugins appear in the list too.

= How does hiding by capability work? =
Add a capability, such as `edit_posts`, and anyone who has it won't see the admin bar. This helps when a membership or LMS plugin grants capabilities rather than roles.

= Can I disable the WordPress admin bar without code? =
Yes — that's exactly what this plugin does. Instead of adding `show_admin_bar` snippets to functions.php (which are lost when you switch themes), you get a settings page with role, capability and guest rules.

= Does this plugin affect administrators? =
Only if you choose to. Administrators keep the admin bar unless you turn on “Hide for everyone”, select the Administrator role, or add a capability they have.

= Does it hide the admin bar inside the dashboard too? =
No. The plugin controls the admin bar on the front end of your site. Inside the dashboard, WordPress always shows it.

= Will this plugin slow down my site? =
No. It adds no CSS or JavaScript to the front end, and the visibility check runs once per page, before the page is built.

= Does it work with my theme and page builder? =
Yes. It works with any theme and with page builders such as Elementor, Divi, Beaver Builder and Bricks.

= Does it work on multisite? =
Yes. The plugin supports WordPress Multisite networks.

= Is there a Pro version? =
No. Hide Admin Bar is completely free, with no locked features and no upsells.

== Screenshots ==

1. Choose who doesn't see the admin bar: everyone, logged-out visitors, specific roles, or users with certain capabilities.
2. “Hide for everyone” removes the admin bar for every logged-in user.

== Changelog ==

= 7.3.0 =
* New: Redesigned settings page that looks and works like the rest of WordPress. Changes save without reloading the page.
* New: Add capabilities as tags. They are no longer cut off at 20 characters.
* Improved: The plugin no longer loads any CSS or JavaScript (including jQuery) on the front end of your site.
* Improved: The settings page loads one small stylesheet and script instead of Bootstrap and other libraries, making the plugin about 2 MB smaller.
* Improved: The plugin is now completely free. Upgrade prompts and ads for paid plans are gone.
* Updated: Freemius SDK 2.13.4.

= 7.2.5 =
* Fixed: Minor compatibility issues with the latest WordPress version.

= 7.2.4 =
* Fixed: Minor compatibility issues with the latest WordPress version.

= 7.2.3 =
* Fixed: Minor compatibility issues with the latest WordPress version.

= 7.2.1 =
* Fixed: Minor compatibility issues with the latest WordPress version.

= 7.2.0 =
* Improved: Admin settings UI refresh for better usability.
* Fixed: Minor compatibility issues with the latest WordPress version.

= 7.1.0 =
* Improved: Admin settings UI refresh for better usability.
* Improved: Code quality and inline documentation.
* Fixed: Minor compatibility issues with the latest WordPress version.

= 7.0.3 - 31-01-2026 =
* Fixed: Minor bugs and stability improvements.

= 7.0.1 - 25-11-2025 =
* Fixed: Minor bugs and stability improvements.

= 6.0.3 - 03-07-2025 =
Fixed some bugs and improvements.

= 6.0.2 - 03-06-2025 =
Fixed some bugs and improvements.

= 6.0.0 - 20-04-2025 =
* Major release: Pro version launched (optional, upgrade via Freemius)
* Added: Auto Hide Admin Bar (Pro)
* Added: Page-based, device-based, and time-based visibility modules (Pro)
* Added: Per-user override, redirect on hide, import/export
* Improved: Settings layout and compatibility with other plugins
* Refactored: Modular architecture for clean feature handling

= 5.2.0 - 02-04-2025 =
* I18N Issues Fixes
* Compatibility tested & Tested with the latest WordPress version.

= 5.0.0 - 16-02-2025 =
* Admin ui updated.
* Compatibility tested & Tested with the latest WordPress version.

= 4.1.0 - 28-12-2024 =
Compatibility tested & Tested with the latest WordPress version.

= 4.0.0 - 09-11-2024 =
Compatibility tested & Tested with the latest WordPress version.

= 3.9.1 - 30-10-2024 =
Compatibility tested & Tested with the latest WordPress version.

= 3.9.0 - 25-09-2024 =
Compatibility tested & Tested with the latest WordPress version.

= 3.8.3 - 13-08-2024 =
Compatibility tested & Tested with the latest WordPress version.

= 3.8.2 - 01-07-2024 =
Compatibility tested & Tested with the latest WordPress version.

= 3.8.1 - 24-05-2024 =
Compatibility tested & Tested with the latest WordPress version.

= 3.8.0 - 15-05-2024 =
Compatibility tested & Tested with the latest WordPress version & UltimaKit For WP support added.

= 3.7.2 - 03-03-2024 =
Compatibility tested & Tested with the latest WordPress version.

= 3.7.1 - 06-02-2024 =
Compatibility tested & Tested with the latest WordPress version.

= 3.7.0 - 01-01-2024 =
Compatibility tested & Tested with the latest WordPress version.

= 3.6.2 - 09-11-2023 =
Compatibility tested & Tested with the latest WordPress version.

= 3.6.1 - 01-10-2023 =
Compatibility tested & Tested with the latest WordPress version.

= 3.6.0 - 02-08-2023 =
Compatibility tested & Tested with the latest WordPress version.

= 3.5.6 =
Compatibility tested & Tested with the latest WordPress version.

= 3.5.5 =
Compatibility tested & Tested with the latest WordPress version.

= 3.5.4 =
Compatibility tested & Tested with the latest WordPress version.

= 3.5.3 =
Compatibility tested & Tested with the latest WordPress version.

= 3.5.2 =
Security Fixes and Improvements.

= 3.5.1 =
Security Fixes and Improvements.

= 3.5.0 =
Security Fixes and Improvements.

= 3.4.1 =
Security Fixes and Improvements.

= 3.4.0 =
Security Fixes and Improvements and multisite support reverted.

= 3.3.0 =
Security Fixes and Improvements and multisite support added.

= 3.2.0 =
Security Fixes and Improvements.

= 3.1.0 =
Security Fixes and Improvements.

= 3.0.0 =
Security Fixes and Improvements.

= 2.9.0 =
Improvements & Fixes

= 2.8.0 =
Improvements & Fixes

= 2.7.0 =
Improvements & Fixes

= 2.6.0 =
Improvements

= 2.5.0 =
Improvements

= 2.4.0 =
Improvements

= 2.3.0 =
Improvements

= 2.2.0 =
Improvements

= 2.1.0 =
Improvements

= 2.0.0 =
Bug Fixes & Improvements

= 1.8.0 =
Bug Fixes & Improvements

= 1.7.0 =
Bug Fixes & Improvements

= 1.6.1 =
Bug Fixes & Improvements

= 1.6 =
Bug Fixes & Improvements

= 1.5 =
Bug Fixes & Improvements

= 1.4 =
Code Improvements, GUI Updated, New Feature Integration.

= 1.3 =
Code Improvements, GUI Updated.

= 1.2 =
Fixed some bugs and improvements and new feature added -> Hide admin bar for all guest users.

= 1.1 =
Fixed some bugs and improvements.

= 1.0 =
First release

== Upgrade Notice ==

= 7.3.0 =
A redesigned settings page, nothing added to your site's front end, and the plugin is now completely free. Your settings are kept.
