<?php

defined( 'ABSPATH' ) || exit;

class Woo_Free_Gift_Add {

	private static $gift_cart_ids = array();
	private static $is_correct_logged_condition;
	private static $is_correct_cart_total_condition;

	/**
	 * Initialization.
	 */
	public static function init() {

		if ( is_admin() ) {
			return;
		}

		add_action( 'woocommerce_before_calculate_totals', __CLASS__ . '::gift_checkout_process', 10, 1 );
		add_action( 'woocommerce_calculate_totals', __CLASS__ . '::check_cart_total_condition', 10, 1 );
		add_action( 'woocommerce_after_calculate_totals', __CLASS__ . '::clean_cart', 10, 1 );
		add_filter( 'woocommerce_cart_item_quantity', __CLASS__ . '::change_gift_qty_input_in_cart', 10, 3 );
		add_filter( 'woocommerce_cart_item_name', __CLASS__ . '::add_gift_label_in_cart', 10, 3 );
		add_action( 'woocommerce_before_cart_table', __CLASS__ . '::add_gift_message', 20, 1 );
	}

	/**
	 * Checkout process.
	 */
	public static function gift_checkout_process( $cart ) {

		if ( $cart->is_empty() ) {
			return;
		}

		self::$is_correct_logged_condition = Woo_Free_Gift_Helpers::is_correct_logged_condition();

		if ( ! self::$is_correct_logged_condition ) {
			return;
		}

		// If the action is fired only the first time.
		if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 ) {
			return;
		}

		$gifts = Woo_Free_Gift_Helpers::get_valid_gifts();

		// Return if there aren't valid gifts.
		if ( is_null( $gifts ) ) {
			return;
		}

		// Reset gift cart IDs array.
		self::$gift_cart_ids = [];

		// Add gifts to cart.
		foreach ( $gifts as $gift_id => $gift_qty ) {
			// Generate unique ID for the gift in cart.
			$gift_cart_id                     = $cart->generate_cart_id( $gift_id );
			self::$gift_cart_ids[ $gift_qty ] = $gift_cart_id;
			$gift_cart_item                   = $cart->get_cart_item( $gift_cart_id );
			$gift_price                       = 0;

			// Check if gift is already in cart.
			if ( ! empty( $cart->find_product_in_cart( $gift_cart_id ) ) ) {

				// Set gift's quantity to its initial value (from settings).
				$cart->set_quantity( $gift_cart_id, $gift_qty );

			} else {

				// Add gift to cart.
				$cart->add_to_cart( $gift_id, $gift_qty );
			}

			if ( ! empty( $gift_cart_item ) ) {
				// Set gift's price.
				$gift_cart_item['data']->set_price( $gift_price );
			}
		}
	}

	/**
	 * Check cart total condition.
	 */
	public static function check_cart_total_condition() {
		self::$is_correct_cart_total_condition = Woo_Free_Gift_Helpers::is_correct_cart_total_condition();
	}

	/**
	 * Clean cart.
	 */
	public static function clean_cart( $cart ) {

		if ( $cart->is_empty() ) {
			return;
		}

		if ( ! self::$is_correct_logged_condition ) {
			return;
		}

		// Remove gifts from the cart if total is not enough.
		if ( ! self::$is_correct_cart_total_condition ) {

			if ( is_array( self::$gift_cart_ids ) && ! empty( self::$gift_cart_ids ) ) {

				foreach ( self::$gift_cart_ids as $gift_cart_id ) {
					WC()->cart->remove_cart_item( $gift_cart_id );
				}
			}

			return;
		}

		// If cart contains only gifts empty it.
		if ( Woo_Free_Gift_Helpers::is_only_gifts_in_cart( self::$gift_cart_ids ) ) {
			$cart->empty_cart();
		}
	}

	/**
	 * Change quantity input in cart to text.
	 */
	public static function change_gift_qty_input_in_cart( $product_quantity, $cart_item_key, $cart_item ) {

		// If there aren't valid gifts return initial quantity.
		if ( empty( self::$gift_cart_ids ) ) {
			return $product_quantity;
		}

		if ( ! self::$is_correct_logged_condition || ! self::$is_correct_cart_total_condition ) {
			return $product_quantity;
		}

		// If current product is a gift.
		if ( in_array( $cart_item_key, self::$gift_cart_ids ) ) {
			$gift_quantity    = array_search( $cart_item_key, self::$gift_cart_ids );
			$product_quantity = sprintf( '%s <input type="hidden" name="cart[%s][qty]" value="%s" />', $gift_quantity, $cart_item_key, $gift_quantity );
		}

		return $product_quantity;
	}

	/**
	 * Add 'Gift' label for product name in cart.
	 */
	public static function add_gift_label_in_cart( $product_name, $cart_item, $cart_item_key ) {

		// If there aren't valid gifts return initial name.
		if ( empty( self::$gift_cart_ids ) ) {
			return $product_name;
		}

		// If show labels option is turned off.
		if ( 'yes' !== get_option( 'woocommerce_woo_free_gift_show_gift_label_in_cart' ) ) {
			return $product_name;
		}

		if ( ! self::$is_correct_logged_condition || ! self::$is_correct_cart_total_condition ) {
			return $product_name;
		}

		$product_name_postfix = '';

		// If current product is a gift.
		if ( in_array( $cart_item_key, self::$gift_cart_ids ) ) {
			$product_name_postfix = '<span class="woo_free_gift_label">' . apply_filters(
					'woo_free_gift_product_name_postfix',
					sprintf( ' - %s', esc_html__( 'Free Gift', 'woo-free-gift' ) )
				) . '</span>';
		}

		return $product_name . $product_name_postfix;
	}

	/**
	 * Add message.
	 */
	public static function add_gift_message() {

		if ( ! Woo_Free_Gift_Helpers::is_any_gift_in_cart( self::$gift_cart_ids ) ) {
			return;
		}

		if ( ! self::$is_correct_logged_condition || ! self::$is_correct_cart_total_condition ) {
			return;
		}

		$gift_message = sanitize_text_field( get_option( 'woocommerce_woo_free_gift_message' ) );

		if ( empty( $gift_message ) ) {
			return;
		}

		wc_clear_notices();
		wc_add_notice( $gift_message, 'notice' );
	}
}

Woo_Free_Gift_Add::init();