<?php
/**
 * Check request
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2021 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Gateways\DigiWallet
 */

namespace Pronamic\WordPress\Pay\Gateways\DigiWallet;

use Pronamic\WordPress\Pay\Core\GatewayConfig;

/**
 * Check request
 *
 * @link    https://www.digiwallet.nl/en/documentation/ideal#checkapi
 * @author  Remco Tolsma
 * @version 1.0.0
 * @since   1.0.0
 */
class CheckRequest extends Request {
	/**
	 * Construct config object.
	 *
	 * @param string $rtlo RTLO.
	 */
	public function __construct( $rtlo, $transaction_number ) {
		parent::__construct( null, $rtlo );

		$this->transaction_number = $transaction_number;
	}

	/**
	 * Get parameters.
	 *
	 * @return array
	 */
	public function get_parameters() {
		$parameters = parent::get_parameters();

		$parameters['trxid'] = $this->transaction_number;
		$parameters['once']  = '0';

		return $parameters;
	}
}
