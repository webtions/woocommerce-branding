===  Custom Branding for WooCommerce ===
Contributors: themeist, hchouhan
Donate link: https://themeist.com/plugins/wordpress/woocommerce-branding/
Tags: woocommerce, branding, woocommerce menu, woocommerce icon
Requires at least: 6.0
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 1.1.0
License: GPL-3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.txt


Replace WooCommerce branding with your own logo and name


== Description ==

This plugin allows you to quickly rebrand WooCommerce in the WordPress admin area by replacing the default WooCommerce name and menu icon with your own.

It's perfect for developers who build client sites and want to customise the admin experience.

If you find this plugin useful, please consider [leaving a review](https://wordpress.org/support/plugin/woocommerce-branding/reviews/?rate=5#new-post). It helps others discover the plugin and supports continued development.

== Installation ==

1. In your WordPress dashboard, go to **Plugins → Add New**.
2. Search for **Custom Branding for WooCommerce**.
3. Click **Install Now** and then **Activate**.
4. Go to **Settings → WC Branding** to customise the branding.


== Frequently Asked Questions ==

= Where can I ask for support? =

You can use the [support forum on WordPress.org](https://wordpress.org/support/plugin/woocommerce-branding/) to report bugs or ask questions.



== Changelog ==

= 1.1.0 - (28 July 2025) =
* Removed `wp_enqueue_media()` and custom JavaScript for cleaner settings page
* Added visibility declarations to all class methods
* Escaped all translatable and dynamic output using `esc_html__()`, `esc_attr()`, and `esc_url()`
* Used strict comparison (`true`) in `in_array()` checks
* Sanitized and unslashed `$_GET['page']` in settings asset loader
* Added full docblocks to all public methods for better developer clarity
* Cleaned up code formatting and ensured PHPCS compliance

= 1.0.1 =
* Minor internal code improvements and documentation updates

= 1.0.0 =
* Initial release
