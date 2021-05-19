<?php
/**
 * Start iDEAL request
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2021 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Gateways\DigiWallet
 */

namespace Pronamic\WordPress\Pay\Gateways\DigiWallet;

/**
 * Start iDEAL request
 *
 * @link    https://www.digiwallet.nl/nl/documentation/ideal
 * @author  Remco Tolsma
 * @version 1.0.0
 * @since   1.0.0
 */
class IDealStartRequest extends StartRequest {
	/**
	 * Construct start iDEAL request
	 *
	 * @param string $rtlo        RTLO.
	 * @param string $amount      Amount.
	 * @param string $description Description.
	 * @param string $return_url  Return URL.
	 */
	public function __construct( $rtlo, $amount, $description, $return_url ) {
		parent::__construct( '4', $rtlo, $amount, $description, $return_url );
	}
}
