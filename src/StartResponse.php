<?php
/**
 * Start response
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2021 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Gateways\DigiWallet
 */

namespace Pronamic\WordPress\Pay\Gateways\DigiWallet;

use Pronamic\WordPress\Pay\Core\GatewayConfig;

/**
 * Start response
 *
 * @link    https://www.digiwallet.nl/nl/documentation/ideal
 * @author  Remco Tolsma
 * @version 1.0.0
 * @since   1.0.0
 */
class StartResponse {
	/**
	 * Result Code.
	 *
	 * @var ResultCode
	 */
	private $result_code;

	/**
	 * Transaction Number.
	 *
	 * @var string
	 */
	private $transaction_number;

	/**
	 * Bank URL.
	 *
	 * @var string
	 */
	private $bank_url;

	/**
	 * Construct config object.
	 *
	 * @param string $rtlo RTLO.
	 */
	public function __construct( $result_code, $transaction_number, $bank_url ) {
		$this->result_code        = $result_code;
		$this->transaction_number = $transaction_number;
		$this->bank_url           = $bank_url;
	}

	/**
	 * Get transaction number.
	 *
	 * @return string
	 */
	public function get_transaction_number() {
		return $this->transaction_number;
	}

	/**
	 * Get bank URL.
	 *
	 * @return string
	 */
	public function get_bank_url() {
		return $this->bank_url;
	}
}
