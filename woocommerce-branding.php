<?php
/*
 * Plugin Name: WooCommerce Branding
 * Plugin URI: http://www.dreamsonline.net/wordpress-plugins/wordpress-for-my-clients/
 * Description: Helps customize WordPress for your clients by hiding non essential wp-admin components and by adding support for custom login logo and favicon for website and admin pages.
 * Version: 1.0.1
 * Author: Dreams Online Themes
 * Author URI: http://www.dreamsonline.net/wordpress-themes/
 * Author Email: hello@dreamsmedia.in
 *
 * @package WordPress
 * @subpackage DOT_WooCommerce_Branding
 * @author Harish
 * @since 1.0
 *
 * License:

	Copyright 2013 "WooCommerce Branding WordPress Plugin" (hello@dreamsmedia.in)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if ( ! class_exists( 'DOT_WooCommerce_Branding' ) ) {


	class DOT_WooCommerce_Branding {

		/*
		--------------------------------------------*
		 * Constructor
		 *--------------------------------------------*/

		/**
		 * Initializes the plugin by setting localization, filters, and administration functions.
		 */
		function __construct() {

			// Load text domain
			add_action( 'init', array( $this, 'load_localisation' ), 0 );

			// Adding Plugin Menu
			add_action( 'admin_menu', array( &$this, 'dot_wcb_menu' ) );

			// Load our custom assets.
			add_action( 'admin_enqueue_scripts', array( &$this, 'dot_wcb_assets' ) );

			// Register Settings
			add_action( 'admin_init', array( &$this, 'dot_wcb_settings' ) );

			// WooCommerce Branding
			/**
			 * Check if WooCommerce is active
			 */
			if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
				add_filter( 'gettext', array( &$this, 'dot_wcb_woocommerce_menu_title' ) );
				add_filter( 'ngettext', array( &$this, 'dot_wcb_woocommerce_menu_title' ) );

				// Add WooCommerce Icon to website backend
				add_action( 'admin_head', array( &$this, 'dot_wcb_woocommerce_icon' ) );
			}
		} // end constructor


		/*
		--------------------------------------------*
		 * Localisation | Public | 1.0 | Return : void
		 *--------------------------------------------*/

		public function load_localisation() {
			load_plugin_textdomain( 'dot_wcb', false, basename( __DIR__ ) . '/languages' );
		} // End load_localisation()

		/**
		 * Defines constants for the plugin.
		 */
		function constants() {
			define( 'DOT_WCB_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );
		}

		/*
		--------------------------------------------*
		 * Admin Menu
		 *--------------------------------------------*/

		function dot_wcb_menu() {
			$page_title = __( 'WC Branding', 'dot_wpfmc' );
			$menu_title = __( 'WC Branding', 'dot_wpfmc' );
			$capability = 'manage_options';
			$menu_slug  = 'dot_wcb';
			$function   = array( &$this, 'dot_wcb_menu_contents' );
			add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function );
		}   //dot_wpfmc_menu

		/*
		--------------------------------------------*
		 * Load Necessary JavaScript Files
		 *--------------------------------------------*/

		function dot_wcb_assets() {
			if ( isset( $_GET['page'] ) && $_GET['page'] == 'dot_wcb' ) {

				wp_enqueue_style( 'thickbox' ); // Stylesheet used by Thickbox
				wp_enqueue_script( 'thickbox' );
				wp_enqueue_script( 'media-upload' );

				wp_register_script( 'dot_wcb_admin', WP_PLUGIN_URL . '/woocommerce-branding/js/dot_wcb_admin.js', array( 'thickbox', 'media-upload' ) );
				wp_enqueue_script( 'dot_wcb_admin' );
			}
		} //dot_wpfmc_assets

		/*
		--------------------------------------------*
		 * Settings & Settings Page
		 *--------------------------------------------*/

		public function dot_wcb_settings() {

			// Settings
			register_setting( 'dot_wcb_settings', 'dot_wcb_settings', array( &$this, 'settings_validate' ) );

			/**
			 * Check if WooCommerce is active
			 */
			if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

				// WooCommerce Branding
				add_settings_section( 'woocommerce_branding', __( 'Replace WC Branding', 'dot_wcb' ), array( &$this, 'section_woocommerce_branding' ), 'dot_wcb_settings' );

				add_settings_field( 'woocommerce_branding_name', __( 'Name', 'dot_wcb' ), array( &$this, 'section_woocommerce_branding_name' ), 'dot_wcb_settings', 'woocommerce_branding' );

				add_settings_field( 'woocommerce_branding_icon', __( 'Icon URL', 'dot_wcb' ), array( &$this, 'section_woocommerce_branding_icon' ), 'dot_wcb_settings', 'woocommerce_branding' );

			}
		}   //dot_wcb_settings


		/*
		--------------------------------------------*
		 * Settings & Settings Page
		 * dot_wpfmc_menu_contents
		 *--------------------------------------------*/

		public function dot_wcb_menu_contents() {
			?>
			<div class="wrap">
				<!--<div id="icon-freshdesk-32" class="icon32"><br></div>-->
				<div id="icon-options-general" class="icon32"><br></div>
				<h2><?php _e( 'WC Branding', 'dot_wcb' ); ?></h2>

				<form method="post" action="options.php">
					<?php // wp_nonce_field('update-options'); ?>
					<?php settings_fields( 'dot_wcb_settings' ); ?>
					<?php do_settings_sections( 'dot_wcb_settings' ); ?>
					<p class="submit">
						<input name="Submit" type="submit" class="button-primary" value="<?php _e( 'Save Changes', 'dot_wcb' ); ?>" />
					</p>
				</form>
			</div>

			<?php
		}   //dot_wpfmc_menu_contents

		function section_woocommerce_branding() {

			// _e( 'Replace WooComm. branding with your own', 'dot_wcb' );
		}

		function section_woocommerce_branding_name() {
			$options = get_option( 'dot_wcb_settings' );

			?>
				<input type='text' id='dot_wcb_settings[woocommerce_branding_name]' class='regular-text' name='dot_wcb_settings[woocommerce_branding_name]' value='<?php echo sanitize_text_field( $options['woocommerce_branding_name'] ); ?>'/>
			<?php
		}

		function section_woocommerce_branding_icon() {
			$options = get_option( 'dot_wcb_settings' );
			?>
			<span class='upload'>
				<input type='text' id='dot_wcb_settings[woocommerce_branding_icon]' class='regular-text text-upload' name='dot_wcb_settings[woocommerce_branding_icon]' value='<?php echo esc_url( $options['woocommerce_branding_icon'] ); ?>'/>
				<input type='button' class='button button-upload' value='Upload an Icon'/></br>
				<img style='max-width: 300px; display: block;' src='<?php echo esc_url( $options['woocommerce_branding_icon'] ); ?>' class='preview-upload' /><br>
				<?php _e( 'Icon should be 28px x 28px', 'dot_wcb' ); ?>

			</span>
			<?php
		}

		/*
		--------------------------------------------*
		 * Settings Validation
		 *--------------------------------------------*/

		function settings_validate( $input ) {

			return $input;
		}

		/*
		--------------------------------------------*
		 * Plugin Functions
		 *--------------------------------------------*/


		function dot_wcb_woocommerce_menu_title( $translated ) {
			$options = get_option( 'dot_wcb_settings' );
			if ( ! isset( $options['woocommerce_branding_name'] ) ) {
				$options['woocommerce_branding_name'] = '';
			}

			if ( $options['woocommerce_branding_name'] == '' ) {
				return $translated;

			} else {
				$translated = str_replace( 'WooCommerce', sanitize_text_field( $options['woocommerce_branding_name'] ), $translated );
				$translated = str_replace( 'WooCommerce', sanitize_text_field( $options['woocommerce_branding_name'] ), $translated );
				return $translated;
			}
		}

		function dot_wcb_woocommerce_icon() {

			$options = get_option( 'dot_wcb_settings' );

			if ( $options['woocommerce_branding_icon'] != '' ) {
				echo '<style type="text/css">
					#adminmenu #toplevel_page_woocommerce div.wp-menu-image {
						background-image: url(' . esc_url( $options['woocommerce_branding_icon'] ) . ') ;
						background-size: auto;
						background-position: 0 0;
					}
	        		</style>';
			}
		}
	} // End Class


	// Initiation call of plugin
	$dot_woocommerce_branding = new DOT_WooCommerce_Branding( __FILE__ );

}



?>