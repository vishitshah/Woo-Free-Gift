<?php
/**
 * Plugin Name: Free Gift for WooCommerce
 * Plugin URI: https://wordpress.org/plugins/woo-free-gift/
 * Description: This simple plugin for WooСommerce allows to specify a free product for all customers which will be added to the cart automatically and always.
 * Version: 1.0
 * Author: vishitshah
 * Author URI: https://vishitshah.com
 * Text Domain: woo-free-gift
 * Domain Path: /languages
 *
 * Requires at least: 5.2
 * Requires PHP: 7.4
 *
 * WC requires at least: 7.3.0
 * WC tested up to: 8.7.0
 *
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 */

defined( 'ABSPATH' ) || exit;

// Check if required function is exist
if ( ! function_exists( 'is_plugin_active' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

// Register theme text domain
add_action( 'plugins_loaded', function () {
	load_plugin_textdomain( 'woo-free-gift', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
} );

// Check if WooCommerce is active and include plugin settings and classes
if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
	require __DIR__ . '/includes/class-wc-settings.php';
	require __DIR__ . '/includes/class-main.php';
	require __DIR__ . '/includes/class-helpers.php';
	require __DIR__ . '/includes/class-gift-add.php';

} else {
	if ( is_admin() ) {
		require __DIR__ . '/includes/class-wc-inactive.php';
	}

	return;
}