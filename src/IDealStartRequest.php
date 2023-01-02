<?php
/**
 * Start iDEAL request
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2023 Pronamic
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
	 * Bank code.
	 *
	 * Note that if this parameter is not provided, DigiWallet will present
	 * the consumer with its own bank selection screen before sending the
	 * consumer to their bank.
	 *
	 * @link https://www.digiwallet.nl/en/documentation/ideal#startapi
	 * @var string|null
	 */
	private $bank;

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

	/**
	 * Set bank.
	 *
	 * @param string|null $bank Bank code.
	 * @return void
	 */
	public function set_bank( $bank ) {
		$this->bank = $bank;
	}

	/**
	 * Get parameters.
	 *
	 * @return array<string, string>
	 */
	public function get_parameters() {
		$parameters = parent::get_parameters();

		if ( null !== $this->bank ) {
			$parameters['bank'] = $this->bank;
		}

		return $parameters;
	}
}
