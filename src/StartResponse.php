<?php
/**
 * Start response
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2022 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Gateways\DigiWallet
 */

namespace Pronamic\WordPress\Pay\Gateways\DigiWallet;

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
	 * @param ResultCode $result_code        Result code.
	 * @param string     $transaction_number Transaction number.
	 * @param string     $bank_url           Bank URL.
	 */
	public function __construct( ResultCode $result_code, $transaction_number, $bank_url ) {
		$this->result_code        = $result_code;
		$this->transaction_number = $transaction_number;
		$this->bank_url           = $bank_url;
	}

	/**
	 * Get result code.
	 *
	 * @return ResultCode
	 */
	public function get_result_code() {
		return $this->result_code;
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

	/**
	 * Parse a start response form response body string.
	 * 
	 * @param string $body Response body string.
	 * @return self
	 * @throws \InvalidArgumentException Throws exception when repsonse is not according required format.
	 */
	public static function from_response_body( $body ) {
		$space_position = \strpos( $body, ' ' );
		$pipe_position  = \strpos( $body, '|' );

		if ( false === $space_position || false === $pipe_position ) {
			throw new \InvalidArgumentException(
				'Response body is not according "resultaatcode transactienummer|bank-url" format.' 
			);
		}

		$result_code        = \substr( $body, 0, $space_position );
		$transaction_number = \substr( $body, $space_position + 1, $pipe_position - $space_position - 1 );
		$bank_url           = \substr( $body, $pipe_position + 1 );

		return new self( new ResultCode( $result_code ), $transaction_number, $bank_url );
	}
}
