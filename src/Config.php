<?php
/**
 * Config
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2022 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Gateways\DigiWallet
 */

namespace Pronamic\WordPress\Pay\Gateways\DigiWallet;

use JsonSerializable;
use Pronamic\WordPress\Pay\Core\GatewayConfig;

/**
 * Config
 *
 * @author  Remco Tolsma
 * @version 1.0.0
 * @since   1.0.0
 */
class Config extends GatewayConfig implements JsonSerializable {
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
	 * Mode.
	 *
	 * @var string
	 */
	public $mode;

	/**
	 * Construct config object.
	 *
	 * @param string $rtlo RTLO.
	 */
	public function __construct( $rtlo ) {
		$this->rtlo = $rtlo;
		$this->mode = '';
	}

	/**
	 * Is test.
	 *
	 * @return bool
	 */
	public function is_test() {
		return $this->test;
	}

	/**
	 * Set test.
	 *
	 * @param bool $test Test.
	 * @return void
	 */
	public function set_test( $test ) {
		$this->test = $test;
	}

	/**
	 * Get RTLO.
	 *
	 * @return string
	 */
	public function get_rtlo() {
		return $this->rtlo;
	}

	/**
	 * JSON serialize.
	 *
	 * @return object
	 */
	public function jsonSerialize(): object {
		return (object) [
			'rtlo' => $this->rtlo,
			'test' => $this->test,
		];
	}
}
