<?php
/**
 * Plugin Name:       Custom Branding for WooCommerce
 * Plugin URI:        https://themeist.com/plugins/wordpress/woocommerce-branding/#utm_source=wp-plugin&utm_medium=woocommerce-branding&utm_campaign=plugins-page
 * Description:       Helps customise WordPress for your clients by hiding non-essential wp-admin components and adding support for a custom login logo and favicon for both frontend and backend.
 * Version:           1.1.0
 * Requires at least: 6.0
 * Tested up to:      6.8
 * Requires PHP:      7.4
 * Author:            Themeist
 * Author URI:        https://themeist.com/
 * License:           GPL-3.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       woocommerce-branding
 *
 * @package Themeist_WooCommerceBranding
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Themeist_WooCommerce_Branding' ) ) {

	class Themeist_WooCommerce_Branding {

		/**
		 * Initializes the plugin by setting localization, filters, and administration functions.
		 */
		function __construct() {
			add_action( 'admin_menu', array( $this, 'dot_wcb_menu' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'dot_wcb_assets' ) );
			add_action( 'admin_init', array( $this, 'dot_wcb_settings' ) );

			if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
				add_filter( 'gettext', array( $this, 'dot_wcb_woocommerce_menu_title' ) );
				add_filter( 'ngettext', array( $this, 'dot_wcb_woocommerce_menu_title' ) );
				add_action( 'admin_head', array( $this, 'dot_wcb_woocommerce_icon' ) );
			}
		}

		/**
		 * Defines constants for the plugin.
		 */
		function constants() {
			define( 'DOT_WCB_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );
		}

		/**
		 * Register the admin settings page.
		 */
		function dot_wcb_menu() {
			$page_title = __( 'WC Branding', 'woocommerce-branding' );
			$menu_title = __( 'WC Branding', 'woocommerce-branding' );
			$capability = 'manage_options';
			$menu_slug  = 'dot_wcb';
			$function   = array( $this, 'dot_wcb_menu_contents' );
			add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function );
		}

		/**
		 * Load admin assets on settings page.
		 */
		function dot_wcb_assets() {
			if ( isset( $_GET['page'] ) && $_GET['page'] === 'dot_wcb' ) {
				wp_enqueue_media();
				wp_enqueue_script(
					'woocommerce-branding-settings',
					plugins_url( 'js/woocommerce-branding-settings.js', __FILE__ ),
					array( 'jquery' ),
					filemtime( plugin_dir_path( __FILE__ ) . 'js/woocommerce-branding-settings.js' ),
					true
				);
			}
		}

		/**
		 * Register plugin settings and fields.
		 */
		public function dot_wcb_settings() {
			register_setting( 'dot_wcb_settings', 'dot_wcb_settings', array( $this, 'settings_validate' ) );

			if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
				add_settings_section(
					'woocommerce_branding',
					__( 'Replace WC Branding', 'woocommerce-branding' ),
					array( $this, 'section_woocommerce_branding' ),
					'dot_wcb_settings'
				);

				add_settings_field(
					'woocommerce_branding_name',
					__( 'Name', 'woocommerce-branding' ),
					array( $this, 'section_woocommerce_branding_name' ),
					'dot_wcb_settings',
					'woocommerce_branding'
				);

				add_settings_field(
					'woocommerce_branding_icon',
					__( 'Icon URL', 'woocommerce-branding' ),
					array( $this, 'section_woocommerce_branding_icon' ),
					'dot_wcb_settings',
					'woocommerce_branding'
				);
			}
		}

		/**
		 * Output the settings page contents.
		 */
		public function dot_wcb_menu_contents() {
			?>
			<div class="wrap">
				<div id="icon-options-general" class="icon32"><br></div>
				<h2><?php _e( 'WC Branding', 'woocommerce-branding' ); ?></h2>

				<form method="post" action="options.php">
					<?php
					settings_fields( 'dot_wcb_settings' );
					do_settings_sections( 'dot_wcb_settings' );
					?>
					<p class="submit">
						<input name="Submit" type="submit" class="button-primary" value="<?php _e( 'Save Changes', 'woocommerce-branding' ); ?>" />
					</p>
				</form>
			</div>
			<?php
		}

		/**
		 * Outputs the section description (currently empty).
		 */
		function section_woocommerce_branding() {
			// Intentionally left blank.
		}

		/**
		 * Outputs the input field for the WooCommerce branding name setting.
		 */
		function section_woocommerce_branding_name() {
			$options = get_option( 'dot_wcb_settings' );
			if ( ! is_array( $options ) ) {
				$options = array();
			}
			$branding_name = sanitize_text_field( $options['woocommerce_branding_name'] ?? '' );
			?>
			<input
				type="text"
				id="dot_wcb_settings[woocommerce_branding_name]"
				name="dot_wcb_settings[woocommerce_branding_name]"
				class="regular-text"
				value="<?php echo esc_attr( $branding_name ); ?>"
			/>
			<?php
		}

		/**
		 * Outputs the input field for the WooCommerce branding icon URL setting.
		 */
		function section_woocommerce_branding_icon() {
			$options = get_option( 'dot_wcb_settings' );
			if ( ! is_array( $options ) ) {
				$options = array();
			}
			$icon_url = esc_url( $options['woocommerce_branding_icon'] ?? '' );
			?>
			<span class="upload">
				<input
					type="text"
					id="dot_wcb_settings[woocommerce_branding_icon]"
					class="regular-text text-upload"
					name="dot_wcb_settings[woocommerce_branding_icon]"
					value="<?php echo $icon_url; ?>"
				/>
				<input type="button" class="button button-upload" value="<?php esc_attr_e( 'Upload an Icon', 'woocommerce-branding' ); ?>" /><br>
				<img style="max-width: 300px; display: block;" src="<?php echo $icon_url; ?>" class="preview-upload" /><br>
				<?php _e( 'Icon should be 28px x 28px', 'woocommerce-branding' ); ?>
			</span>
			<?php
		}

		/**
		 * Validate and sanitize plugin settings before saving.
		 *
		 * @param array $input Settings array.
		 * @return array Sanitized settings.
		 */
		function settings_validate( $input ) {
			return $input;
		}

		/**
		 * Replaces the WooCommerce menu label with the custom branding name.
		 *
		 * @param string $translated The translated string.
		 * @return string Modified menu title if branding name is set, original otherwise.
		 */
		function dot_wcb_woocommerce_menu_title( $translated ) {
			$options = get_option( 'dot_wcb_settings' );
			if ( ! is_array( $options ) ) {
				$options = array();
			}
			$branding_name = sanitize_text_field( $options['woocommerce_branding_name'] ?? '' );
			return $branding_name !== '' ? str_replace( 'WooCommerce', $branding_name, $translated ) : $translated;
		}

		/**
		 * Injects custom CSS to replace the WooCommerce admin menu icon with a custom branding icon.
		 */
		function dot_wcb_woocommerce_icon() {
			$options = get_option( 'dot_wcb_settings' );
			if ( ! is_array( $options ) ) {
				return;
			}
			$icon_url = esc_url( $options['woocommerce_branding_icon'] ?? '' );
			if ( $icon_url !== '' ) {
				?>
				<style type="text/css">
					#adminmenu #toplevel_page_woocommerce div.wp-menu-image {
						background-image: url('<?php echo $icon_url; ?>');
						background-size: auto;
						background-position: 0 0;
					}
				</style>
				<?php
			}
		}
	} // End class

	// Initiation call of plugin
	new Themeist_WooCommerce_Branding( __FILE__ );
}
