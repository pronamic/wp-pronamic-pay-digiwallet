<?php
/**
 * Result Code
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2023 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Gateways\DigiWallet
 */

namespace Pronamic\WordPress\Pay\Gateways\DigiWallet;

/**
 * Result Code
 *
 * @link    https://www.digiwallet.nl/nl/documentation/ideal
 * @author  Remco Tolsma
 * @version 1.0.0
 * @since   1.0.0
 */
class ResultCode {
	/**
	 * Result code OK.
	 *
	 * @var string
	 */
	const OK = '000000';

	/**
	 * Result Code.
	 *
	 * @var string
	 */
	private $code;

	/**
	 * Construct config object.
	 *
	 * @param string $code Code.
	 */
	public function __construct( $code ) {
		$this->code = $code;
	}

	/**
	 * Check if this result code is OK or not.
	 *
	 * @return bool True if OK, false otherwise.
	 */
	public function is_ok() {
		return self::OK === $this->code;
	}

	/**
	 * Check if this result code is an error.
	 *
	 * @return bool True if error, false otherwise.
	 */
	public function is_error() {
		return ! $this->is_ok();
	}

	/**
	 * String.
	 *
	 * @return string
	 */
	public function __toString() {
		return $this->code;
	}
}
