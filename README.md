# Custom Branding for WooCommerce

> Replace the **WooCommerce** admin menu name and icon with your own — simple, clean, and perfect for client sites.

Ideal for freelancers and agencies who want to deliver a white-labeled experience in the WordPress admin.

If you find this plugin helpful, please consider [leaving a review](https://wordpress.org/support/plugin/woocommerce-branding/reviews/?rate=5#new-post) — it supports future updates and helps others find the plugin.

---

## Features

- Change WooCommerce admin menu name
- Replace the menu icon with any Dashicon or SVG
- No bloat – just one clean settings screen
- No frontend impact
- Fully compliant with WordPress Coding Standards

---

## Security

If you discover a security vulnerability in this plugin, please report it privately to `mail@webtions.com` so it can be addressed responsibly.

---

## License

GPL-3.0
See [license details here](http://www.gnu.org/licenses/gpl-3.0.txt)

---

## Development

<details>
<summary><strong>Show setup instructions</strong></summary>

### Clone and Install

```bash
git clone https://github.com/webtions/woocommerce-branding.git
cd woocommerce-branding
composer install
```

### Useful Commands

Check for coding standard violations:

```bash
composer standards:check
```

Fix fixable code style issues:

```bash
composer standards:fix
```

Run static analysis:

```bash
composer analyze
```

> This plugin follows the official [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/).
</details>

---

## Changelog

<details>
<summary><strong>View changelog</strong></summary>

### 1.1.0 - (28 July 2025)
- Removed `wp_enqueue_media()` and custom JavaScript for cleaner settings page
- Added visibility declarations to all class methods
- Escaped all translatable and dynamic output using `esc_html__()`, `esc_attr()`, and `esc_url()`
- Used strict comparison (`true`) in `in_array()` checks
- Sanitized and unslashed `$_GET['page']` in settings asset loader
- Added full docblocks to all public methods for better developer clarity
- Cleaned up code formatting and ensured PHPCS compliance

### 1.0.1
- Minor internal code improvements and documentation updates

### 1.0.0
- Initial release

</details>
