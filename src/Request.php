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

use Pronamic\WordPress\Pay\Core\GatewayConfig;

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
	 * @var string
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
	 * @param string $mode Mode.
	 * @param string $rtlo RTLO.
	 */
	public function __construct( $version, $rtlo ) {
		$this->version = $version;
		$this->rtlo    = $rtlo;
	}

	/**
	 * Set test.
	 */
	public function set_test( $test ) {
		$this->test = $test;
	}

	/**
	 * Get parameters.
	 *
	 * @return array
	 */
	public function get_parameters() {
		return array(
			'ver'  => $this->version,
			'rtlo' => $this->rtlo,
			'test' => $this->test ? '1' : '0',
		);
	}
}
