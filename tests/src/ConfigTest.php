<?php
/**
 * Config test
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2021 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Gateways\Adyen
 */

namespace Pronamic\WordPress\Pay\Gateways\DigiWallet;

use PHPUnit\Framework\TestCase;

/**
 * Config test
 *
 * @link https://docs.adyen.com/developers/development-resources/live-endpoints
 *
 * @author  Remco Tolsma
 * @version 1.0.0
 * @since   1.0.0
 */
class ConfigTest extends TestCase {
	/**
	 * Test config.
	 */
	public function test_config() {
		$config = new Config( '123456' );

		$this->assertEquals( '123456', $config->get_rtlo() );
		$this->assertFalse( $config->is_test() );

		$config->set_test( true );

		$this->assertTrue( $config->is_test() );
	}
}
