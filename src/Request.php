<?php
/**
 * Request
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2021 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Gateways\DigiWallet
 */

namespace Pronamic\WordPress\Pay\Gateways\DigiWallet;

/**
 * Request
 *
 * @author  Remco Tolsma
 * @version 1.0.0
 * @since   1.0.0
 */
abstract class Request {
	/**
	 * Version.
	 *
	 * @var string|null
	 */
	private $version;

	/**
	 * RTLO.
	 *
	 * @var string
	 */
	private $rtlo;

	/**
	 * Test.
	 *
	 * @var bool
	 */
	private $test = false;

	/**
	 * Construct config object.
	 *
	 * @param string $version Version.
	 * @param string $rtlo    RTLO.
	 */
	public function __construct( $version, $rtlo ) {
		$this->version = $version;
		$this->rtlo    = $rtlo;
	}

	/**
	 * Set test.
	 *
	 * @param bool $test Test.
	 */
	public function set_test( $test ) {
		$this->test = $test;
	}

	/**
	 * Get parameters.
	 *
	 * @return array<string, string>
	 */
	public function get_parameters() {
		$parameters = array();

		if ( null !== $this->version ) {
			$parameters['ver'] = $this->version;
		}

		$parameters['rtlo'] = $this->rtlo;
		$parameters['test'] = $this->test ? '1' : '0';

		return $parameters;
	}
}
