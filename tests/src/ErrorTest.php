<?php
/**
 * Error test
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2021 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Gateways\Adyen
 */

namespace Pronamic\WordPress\Pay\Gateways\DigiWallet;

use PHPUnit\Framework\TestCase;

/**
 * Error test
 *
 * @author  Remco Tolsma
 * @version 1.0.0
 * @since   1.0.0
 */
class ErrorTest extends TestCase {
	/**
	 * Test error.
	 */
	public function test_config() {
		$result_code = new ResultCode( 'DW_IE_0002' );

		$error = new Error( $result_code, 'Maximum retries at acquirer bank exceeded for primary and fallback' );

		$this->assertEquals( $result_code, $error->get_result_code() );
		$this->assertEquals( 'Maximum retries at acquirer bank exceeded for primary and fallback', $error->getMessage() );
	}
}
