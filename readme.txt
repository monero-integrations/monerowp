=== Monero WooCommerce Extension ===
Contributors: serhack
Donate link: http://monerointegrations.com/donate.html
Tags: monero, woocommerce, integration, payment, merchant, cryptocurrency, accept monero, monero woocommerce
Requires at least: 4.0
Tested up to: 4.8
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
 
Monero WooCommerce Extension is a Wordpress plugin that allows to accept bitcoins at WooCommerce-powered online stores.

== Description ==

An extension to WooCommerce for accepting Monero as payment in your store.

= Benefits =

* Accept payment directly into your personal Monero wallet.
* Accept payment in monero for physical and digital downloadable products.
* Add monero payments option to your existing online store with alternative main currency.
* Flexible exchange rate calculations fully managed via administrative settings.
* Zero fees and no commissions for monero payments processing from any third party.
* Automatic conversion to Monero via realtime exchange rate feed and calculations.
* Ability to set exchange rate calculation multiplier to compensate for any possible losses due to bank conversions and funds transfer fees.

== Installation ==

1. Install "Monero WooCommerce extension" wordpress plugin just like any other Wordpress plugin.
2. Activate
3. Setup your monero-wallet-rpc with a view-only wallet
4. Add your monero-wallet-rpc host address and Monero address in the settings panel
5. Click “Enable this payment gateway”
6. Enjoy it!

== Remove plugin ==

1. Deactivate plugin through the 'Plugins' menu in WordPress
2. Delete plugin through the 'Plugins' menu in WordPress

== Screenshots == 
1. Monero Payment Box
2. Monero Options

== Changelog ==

= 0.1 =
* First version ! Yay!

= 0.2 =
* Bug fixes

== Upgrade Notice ==

soon

== Frequently Asked Questions ==

* What is Monero ?
Monero is completely private, cryptographically secure, digital cash used across the globe. See https://getmonero.org for more information

* What is a Monero wallet?
A Monero wallet is a piece of software that allows you to store your funds and interact with the Monero network. You can get a Monero wallet from https://getmonero.org/downloads

* What is monero-wallet-rpc ?
The monero-wallet-rpc is an RPC server that will allow this plugin to communicate with the Monero network. You can download it from https://getmonero.org/downloads with the command-line tools.

* Why do I see `[ERROR] Failed to connect to monero-wallet-rpc at localhost port 18080
Syntax error: Invalid response data structure: Request id: 1 is different from Response id: ` ?
This is most likely because this plugin can not reach your monero-wallet-rpc. Make sure that you have supplied the correct host IP and port to the plugin in their fields. If your monero-wallet-rpc is on a different server than your wordpress site, make sure that the appropriate port is open with port forwarding enabled.
