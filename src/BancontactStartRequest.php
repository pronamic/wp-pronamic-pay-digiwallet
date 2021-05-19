<?php
/**
 * Start Bancontact request
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2021 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Gateways\DigiWallet
 */

namespace Pronamic\WordPress\Pay\Gateways\DigiWallet;

/**
 * Start Bancontact request
 *
 * @link    https://www.digiwallet.nl/nl/documentation/bancontact
 * @author  Remco Tolsma
 * @version 1.0.0
 * @since   1.0.0
 */
class BancontactStartRequest extends StartRequest {
	/**
	 * Construct config object.
	 *
	 * @param string $rtlo        RTLO.
	 * @param string $amount      Amount.
	 * @param string $description Description.
	 * @param string $return_url  Return URL.
	 * @param string $user_ip     User IP.
	 */
	public function __construct( $rtlo, $amount, $description, $return_url, $user_ip ) {
		parent::__construct( '2', $rtlo, $amount, $description, $return_url );

		$this->user_ip = $user_ip;
	}
}
