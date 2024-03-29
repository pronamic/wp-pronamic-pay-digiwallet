<?php
/**
 * Plugin Name: Pronamic Pay DigiWallet Add-On
 * Plugin URI: https://www.pronamic.eu/plugins/pronamic-pay-digiwallet/
 * Description: Extend the Pronamic Pay plugin with the DigiWallet gateway to receive payments with DigiWallet through a variety of WordPress plugins.
 *
 * Version: 3.3.4
 * Requires at least: 4.7
 * Requires PHP: 7.4
 *
 * Author: Pronamic
 * Author URI: https://www.pronamic.eu/
 *
 * Text Domain: pronamic-pay-digiwallet
 * Domain Path: /languages/
 *
 * License: GPL-3.0-or-later
 *
 * Requires Plugins: pronamic-ideal
 * Depends: wp-pay/core
 *
 * GitHub URI: https://github.com/wp-pay-gateways/digiwallet
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2023 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Gateways\DigiWallet
 */

add_filter(
	'pronamic_pay_gateways',
	static function( $gateways ) {
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\DigiWallet\Integration();

		return $gateways;
	}
);
