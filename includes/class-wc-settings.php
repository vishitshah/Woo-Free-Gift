<?php

defined( 'ABSPATH' ) || exit;

class Woo_Free_Gift_WC_Settings {

	/**
	 * Bootstraps the class and hooks required actions
	 */
	public static function init() {
		add_filter( 'woocommerce_settings_tabs_array', __CLASS__ . '::add_settings_tab', 50 );
		add_action( 'woocommerce_settings_tabs_woo_free_gift_settings', __CLASS__ . '::settings_tab' );
		add_action( 'woocommerce_update_options_woo_free_gift_settings', __CLASS__ . '::update_settings' );
	}

	/**
	 * Add a new settings tab to the WooCommerce settings tabs array.
	 *
	 * @param array $settings_tabs Array of WooCommerce setting tabs & their labels, excluding the Free Gift tab.
	 *
	 * @return array $settings_tabs Array of WooCommerce setting tabs & their labels, including the Free Gift tab.
	 */
	public static function add_settings_tab( $settings_tabs ) {
		$settings_tabs['woo_free_gift_settings'] = __( 'Free Gift', 'woo-free-gift' );

		return $settings_tabs;
	}

	/**
	 * Uses the WooCommerce admin fields API to output settings via the @see woocommerce_admin_fields() function.
	 *
	 * @uses woocommerce_admin_fields()
	 * @uses self::get_settings()
	 */
	public static function settings_tab() {
		woocommerce_admin_fields( self::get_settings() );
	}

	/**
	 * Uses the WooCommerce options API to save settings via the @see woocommerce_update_options() function.
	 *
	 * @uses woocommerce_update_options()
	 * @uses self::get_settings()
	 */
	public static function update_settings() {
		woocommerce_update_options( self::get_settings() );
	}

	/**
	 * Get all the settings for this plugin for @return array Array of settings for @see woocommerce_admin_fields() function.
	 *
	 * @see woocommerce_admin_fields() function.
	 *
	 */
	public static function get_settings() {
		$settings = [
			// Products and Quantity.
			[
				'title' => __( 'Products and Quantity', 'woo-free-gift' ),
				'desc'  => __( 'Specify the IDs of the products witch will be the gifts.', 'woo-free-gift' ),
				'id'    => 'woocommerce_woo_free_gift_prod_qty_section',
				'type'  => 'title',
			],

			[
				'title'    => __( 'Product IDs', 'woo-free-gift' ),
				'desc'     => sprintf( __( 'For more information read %s this article.', 'woo-free-gift' ), '<a href="https://docs.woocommerce.com/document/find-product-category-ids/" target="_blank">' ),
				'desc_tip' => __( 'Set IDs of products to be a gift separated by a coma (without spaces).', 'woo-free-gift' ),
				'id'       => 'woocommerce_woo_free_gift_ids',
				'type'     => 'text',
				'default'  => '',
			],

			[
				'title'    => __( 'Quantity', 'woo-free-gift' ),
				'desc'     => __( 'Leave the field blank to set the quantity of each gift equal 1.', 'woo-free-gift' ),
				'desc_tip' => __( 'Set quantity for each gift separated by a coma (without spaces).', 'woo-free-gift' ),
				'id'       => 'woocommerce_woo_free_gift_quantity',
				'type'     => 'text',
				'default'  => '1',
			],

			[
				'id'   => 'woocommerce_woo_free_gift_prod_qty_section',
				'type' => 'sectionend',
			],

			// Conditions.
			[
				'title' => __( 'Conditions', 'woo-free-gift' ),
				'desc'  => __( 'Select the conditions for displaying and working gifts.', 'woo-free-gift' ),
				'id'    => 'woocommerce_woo_free_gift_conditions_section',
				'type'  => 'title',
			],

			[
				'title'   => __( 'Label in Cart', 'woo-free-gift' ),
				'desc'    => sprintf(
					__( 'Show %sFree Gift%s labels for the gifts in cart?', 'woo-free-gift' ),
					'<code>',
					'</code>'
				),
				'id'      => 'woocommerce_woo_free_gift_show_gift_label_in_cart',
				'type'    => 'checkbox',
				'default' => 'no',
			],

			[
				'title'   => __( 'Only for Authorized', 'woo-free-gift' ),
				'desc'    => __( 'Give the gifts only for authorized users?', 'woo-free-gift' ),
				'id'      => 'woocommerce_woo_free_gift_only_for_authorized',
				'type'    => 'checkbox',
				'default' => 'no',
			],

			[
				'title'             => sprintf( __( 'Minimum Order Amount in %s', 'woo-free-gift' ), esc_html( get_woocommerce_currency() ) ),
				'desc'              => __( 'Set minimum order amount for adding gifts.', 'woo-free-gift' ),
				'desc_tip'          => __( 'You can leave the field blank to hide this limit.', 'woo-free-gift' ),
				'id'                => 'woocommerce_woo_free_gift_minimum_order_amount',
				'type'              => 'number',
				'custom_attributes' => [
					'min'  => 0,
					'step' => '0.01',
				],
				'css'               => 'width: 80px;',
			],

			[
				'desc'    => __( 'Order amount type.', 'woo-free-gift' ),
				'id'      => 'woocommerce_woo_free_gift_order_total_type',
				'type'    => 'select',
				'default' => 'subtotal',
				'options' => [
					'subtotal' => __( 'Subtotal', 'woo-free-gift' ),
					'total'    => __( 'Total', 'woo-free-gift' ),
				],
				'css'     => 'width: 120px;',
			],

			[
				'title'    => __( 'Message About Gift', 'woo-free-gift' ),
				'desc'     => __( 'Leave the field blank to hide the message.', 'woo-free-gift' ),
				'desc_tip' => __( 'Set text about free gift in WooCommerce notification area.', 'woo-free-gift' ),
				'id'       => 'woocommerce_woo_free_gift_message',
				'type'     => 'textarea',
				'default'  => __( 'Congratulations! You have received a free gift!', 'woo-free-gift' ),
			],

			[
				'id'   => 'woocommerce_woo_free_gift_conditions_section',
				'type' => 'sectionend',
			],
		];

		return apply_filters( 'woocommerce_woo_free_gift_settings', $settings );
	}
}

Woo_Free_Gift_WC_Settings::init();