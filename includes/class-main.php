<?php

defined( 'ABSPATH' ) || exit;

class Woo_Free_Gift_Main {

	static $gift_cart_ids = array();

	/**
	 * Initialization.
	 */
	public static function init() {
		register_activation_hook( plugin_dir_path( __DIR__ ) . 'woo-free-gift.php', [ __CLASS__, 'activation' ] );
		register_uninstall_hook( plugin_dir_path( __DIR__ ) . 'woo-free-gift.php', [ __CLASS__, 'uninstall' ] );
		add_filter( 'plugin_action_links_' . plugin_basename( plugin_dir_path( __DIR__ ) . 'woo-free-gift.php' ), __CLASS__ . '::add_plugin_page_settings_link' );
	}

	/**
	 * Plugin activation.
	 */
	public static function activation() {
		add_option( 'woocommerce_woo_free_gift_message', __( 'Congratulations! You have received a free gift!', 'woo-free-gift' ) );
	}

	/**
	 * Plugin uninstallation.
	 */
	public static function uninstall() {
		delete_option( 'woocommerce_woo_free_gift_prod_qty_section' );
		delete_option( 'woocommerce_woo_free_gift_ids' );
		delete_option( 'woocommerce_woo_free_gift_quantity' );
		delete_option( 'woocommerce_woo_free_gift_conditions_section' );
		delete_option( 'woocommerce_woo_free_gift_show_gift_label_in_cart' );
		delete_option( 'woocommerce_woo_free_gift_only_for_authorized' );
		delete_option( 'woocommerce_woo_free_gift_minimum_order_amount' );
		delete_option( 'woocommerce_woo_free_gift_order_total_type' );
		delete_option( 'woocommerce_woo_free_gift_message' );
	}

	/**
	 * Add plugin page settings link.
	 */
	public static function add_plugin_page_settings_link( $links ) {
		$links[] = '<a href="' .
		           admin_url( 'admin.php?page=wc-settings&tab=woo_free_gift_settings' ) .
		           '">' . __( 'Settings', 'woo-free-gift' ) . '</a>';

		return $links;
	}
}

Woo_Free_Gift_Main::init();