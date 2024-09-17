=== Free Gift for WooCommerce ===
Contributors: vishitshah
Tags: woocommerce, woocommerce gift, free gift, gift, free product
Requires at least: 5.2
Tested up to: 6.5
Requires PHP: 7.4
Stable tag: 1.0
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

== Description ==

This plugin for WooСommerce allows specifying existing products as free gifts for all customers which will be added to the cart automatically.

### Features:
* Appointment of one or more gifts.
* Give gifts only to authorized users.
* Give gifts if the cart totals more or equal to a certain amount.
* Show 'Free Gift' labels for the gifts in the cart.

Tested on Storefront theme.

== Frequently Asked Questions ==

= How to set the free gift(s)? =
- Open `WooCommerce - Settings - Free Gift (tab)` in WordPress admin panel.
- Specify the product IDs to be the gifts, separated a coma, and appropriate gifts quantity.
For example, if you set the ‘Product IDs’ field as `45,112,62` and ‘Quantity’ field as `3,2,1` you will present to your customer 3 products with IDs `45,112,62`.
The first gift (product) will have the quantity 3 pcs., the second – 2 pcs., and the last one – 1 pcs.
- Setup other conditions (show only for authorized users, show labels in cart) if you need them.

= How to hide gifts from catalog? =
- Select the product that you want to be a gift.
- In the section 'Publish' (to the right of the title) click the 'Edit' link next to 'Catalog visibility' and chose the 'Hidden' option.
 Update the product.

= How to know a product ID? =
[Read the article](https://docs.woocommerce.com/document/find-product-category-ids/)

== Installation ==

Install the plugin via Wordpress plugin manager in admin panel.

== Changelog ==

= 0.0.4 =
* Added condition for minimal order amount.

= 0.0.3 =
* Now you can choose several products for the free gift.
* Give the gifts only for authorized users.
* Show 'Free Gift' labels for the gifts in cart.
* Check compatibility with WordPress 5.5.1 and WooCommerce 4.4.1.

== Screenshots ==

1. Cart.
2. Settings.
