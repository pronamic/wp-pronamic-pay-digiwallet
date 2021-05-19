<?php
/**
 * Start request
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2021 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Gateways\DigiWallet
 */

namespace Pronamic\WordPress\Pay\Gateways\DigiWallet;

/**
 * Start request
 *
 * @link    https://www.digiwallet.nl/nl/documentation/ideal
 * @author  Remco Tolsma
 * @version 1.0.0
 * @since   1.0.0
 */
abstract class StartRequest extends Request {
	/**
	 * Construct config object.
	 *
	 * @param string $version     Version.
	 * @param string $rtlo        RTLO.
	 * @param string $amount      Amount.
	 * @param string $description Description.
	 * @param string $return_url  Return URL.
	 */
	public function __construct( $version, $rtlo, $amount, $description, $return_url ) {
		parent::__construct( $version, $rtlo );

		$this->amount      = $amount;
		$this->description = $description;
		$this->return_url  = $return_url;
	}

	/**
	 * Set report URL.
	 *
	 * @param string $url URL.
	 */
	public function set_report_url( $url ) {
		$this->report_url = $url;
	}

	/**
	 * Get parameters.
	 *
	 * @return array<string, string>
	 */
	public function get_parameters() {
		$parameters = parent::get_parameters();

		$parameters['amount']      = $this->amount;
		$parameters['description'] = $this->description;
		$parameters['returnurl']   = $this->return_url;

		if ( null !== $this->user_ip ) {
			$parameters['userip'] = $this->user_ip;
		}

		if ( null !== $this->report_url ) {
			$parameters['reporturl'] = $this->report_url;
		}

		return $parameters;
	}
}
