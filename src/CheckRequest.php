<?php
/**
 * Check request
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2022 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Gateways\DigiWallet
 */

namespace Pronamic\WordPress\Pay\Gateways\DigiWallet;

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
	 * Transaction number.
	 *
	 * @var string
	 */
	private $transaction_number;

	/**
	 * Construct config object.
	 *
	 * @param string $rtlo RTLO.
	 * @param string $transaction_number Transaction number.
	 */
	public function __construct( $rtlo, $transaction_number ) {
		parent::__construct( null, $rtlo );

		$this->transaction_number = $transaction_number;
	}

	/**
	 * Get parameters.
	 *
	 * @return array<string, string>
	 */
	public function get_parameters() {
		$parameters = parent::get_parameters();

		$parameters['trxid'] = $this->transaction_number;
		$parameters['once']  = '0';

		return $parameters;
	}
}
