<?php
/**
 * Start response test
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2022 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Gateways\Adyen
 */

namespace Pronamic\WordPress\Pay\Gateways\DigiWallet;

use PHPUnit\Framework\TestCase;

/**
 * Start response test
 *
 * @author  Remco Tolsma
 * @version 1.0.0
 * @since   1.0.0
 */
class StartResponseTest extends TestCase {
	/**
	 * Test start response.
	 * 
	 * @dataProvider data_provider
	 * @param string $response_body      Response body.
	 * @param string $result_code        Result code.
	 * @param string $transaction_number Expected transaction number.
	 * @param string $bank_url           Expected bank URL.
	 */
	public function test_start_response( $response_body, $result_code, $transaction_number, $bank_url ) {
		$start_response = StartResponse::from_response_body( $response_body );

		$this->assertSame( $result_code, \strval( $start_response->get_result_code() ) );
		$this->assertSame( $transaction_number, $start_response->get_transaction_number() );
		$this->assertSame( $bank_url, $start_response->get_bank_url() );
	}

	/**
	 * Data provider.
	 * 
	 * @return array<array<int, string>>
	 */
	public function data_provider() {
		return [
			/**
			 * PayPal start response.
			 * 
			 * @link https://www.digiwallet.nl/nl/documentation/paymethods/paypal
			 */
			[
				'000000 PAY-XXXXXXXXXXXXXXXXXXXXXXXX|https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=EC-XYXYXYXYXYXYXYXYX',
				'000000',
				'PAY-XXXXXXXXXXXXXXXXXXXXXXXX',
				'https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=EC-XYXYXYXYXYXYXYXYX',
			],
			/**
			 * Unfortunately the DigiWallet document is not correct, when testing PayPal we get the following response:
			 */
			[
				'000000 |https://pay.digiwallet.nl/test-transaction?transactionID=15443&paymethod=PYP&hash=b03eb29f3fe0a3735c52887253a1800e285835505a1058aaaf1c64ec84674c6e',
				'000000',
				'',
				'https://pay.digiwallet.nl/test-transaction?transactionID=15443&paymethod=PYP&hash=b03eb29f3fe0a3735c52887253a1800e285835505a1058aaaf1c64ec84674c6e',
			],
		];
	}
}
