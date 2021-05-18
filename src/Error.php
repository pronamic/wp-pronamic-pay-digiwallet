<?php
/**
 * Error
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2021 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Gateways\DigiWallet
 */

namespace Pronamic\WordPress\Pay\Gateways\DigiWallet;

use Pronamic\WordPress\Pay\Core\GatewayConfig;

/**
 * Error
 *
 * @author  Remco Tolsma
 * @version 1.0.0
 * @since   1.0.0
 */
class Error extends \Exception {
	/**
	 * Result Code.
	 *
	 * @var ResultCode
	 */
	private $result_code;

	/**
	 * Construct error
	 *
	 * @param ResultCode $result_code Result Code.
	 * @param string $rtlo RTLO.
	 */
	public function __construct( $result_code, $message ) {
		parent::__construct( $message );

		$this->result_code = $result_code;
	}
}
