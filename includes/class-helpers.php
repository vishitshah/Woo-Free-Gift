<?php

defined( 'ABSPATH' ) || exit;

class Woo_Free_Gift_Helpers {

	private static $minimal_order_amount = 0;

	/**
	 * Convert settings string to array.
	 */
	public static function convert_string_to_array( $string ) {

		if ( ! is_string( $string ) ) {
			return false;
		}

		$array = array_unique( explode( ',', $string ) );

		$array = array_filter( $array, function ( $value ) {
			return ! empty( (int) ( $value ) ) && (int) ( $value ) > 0;
		} );

		$array = array_map( function ( $value ) {
			return absint( $value );
		}, $array );

		return array_values( $array );
	}

	/**
	 * Get valid gifts.
	 */
	public static function get_valid_gifts() {

		$gift_ids = self::convert_string_to_array( get_option( 'woocommerce_woo_free_gift_ids' ) );

		// Return null if there aren't IDs.
		if ( ! is_array( $gift_ids ) || empty( $gift_ids ) ) {
			return null;
		}

		$gift_ids_count   = count( $gift_ids );
		$default_gift_qty = array_fill( 0, $gift_ids_count, 1 );
		$gift_qty         = self::convert_string_to_array( get_option( 'woocommerce_woo_free_gift_quantity' ) );
		$gift_qty         = ! empty( $gift_qty ) ? $gift_qty : $default_gift_qty;
		$gift_qty_count   = count( $gift_qty );


		// Adjust the number of the quantities to the number of IDs.
		if ( $gift_qty_count > $gift_ids_count ) {
			$gift_qty = array_slice( $gift_qty, 0, $gift_ids_count );
		}

		if ( $gift_qty_count < $gift_ids_count ) {
			$gift_qty = array_merge( $gift_qty, array_fill( 0, $gift_ids_count - $gift_qty_count, 1 ) );
		}

		// Create gifts array.
		$gifts = array_combine( $gift_ids, $gift_qty );

		// Check gift IDs for exist products.
		return array_filter( $gifts, function ( $gift_qty, $gift_id ) {
			return wc_get_product( $gift_id ) !== false;
		}, ARRAY_FILTER_USE_BOTH );
	}

	/**
	 * Get all cart items ids.
	 */
	public static function get_all_cart_items_ids() {

		$cart_ids = array();

		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$cart_ids[] = $cart_item_key;
		}

		return $cart_ids;
	}

	/**
	 * Return true if only gifts are in the cart.
	 */
	public static function is_only_gifts_in_cart( $gift_ids ) {

		$cart_ids = self::get_all_cart_items_ids();

		if ( empty( array_diff( $cart_ids, $gift_ids ) ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Return true if any gifts are in the cart.
	 */
	public static function is_any_gift_in_cart( $gift_ids ) {

		$cart_ids = self::get_all_cart_items_ids();

		if ( empty( array_intersect( $gift_ids, $cart_ids ) ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Return:
	 * true if 'only for authorized users' option is not active.
	 * true if 'only for authorized users' option is active and user is logged (is not a visitor).
	 * false otherwise (if option is active and user is a visitor).
	 */
	public static function is_correct_logged_condition() {

		if ( 'yes' !== get_option( 'woocommerce_woo_free_gift_only_for_authorized' ) ||
		     (
			     'yes' === get_option( 'woocommerce_woo_free_gift_only_for_authorized' ) &&
			     is_user_logged_in()
		     )
		) {
			return true;
		}

		return false;
	}

	/**
	 * Check minimal order amount.
	 * Return true if minimal order amount more than cart total and false otherwise.
	 */
	public static function is_cart_total_enough() {

		$order_total_type = esc_attr( get_option( 'woocommerce_woo_free_gift_order_total_type' ) );

		if ( ! self::$minimal_order_amount || ! $order_total_type ) {
			return false;
		}

		if ( $order_total_type === 'subtotal' ) {
			$cart_total = WC()->cart->get_cart_contents_total();
		} elseif ( $order_total_type === 'total' ) {
			$cart_total = WC()->cart->get_cart_contents_total() + WC()->cart->get_shipping_total() + WC()->cart->get_cart_contents_tax();
		}

		if ( empty( $cart_total ) ) {
			return false;
		}

		if ( $cart_total >= self::$minimal_order_amount ) {
			return true;
		}

		return false;
	}

	/**
	 * Return:
	 * true if 'minimum order amount' option is empty.
	 * true if 'minimum order amount' option is not empty and cart total is enough.
	 * false otherwise (if option is empty and cart total is not enough).
	 */
	public static function is_correct_cart_total_condition() {

		self::$minimal_order_amount = abs( floatval( get_option( 'woocommerce_woo_free_gift_minimum_order_amount' ) ) );

		if ( empty( self::$minimal_order_amount ) ||
		     (
			     ! empty( self::$minimal_order_amount ) &&
			     self::is_cart_total_enough()
		     )
		) {
			return true;
		}

		return false;
	}
}