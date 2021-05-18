<?php
/**
 * Gateway
 *
 * @author Pronamic <info@pronamic.eu>
 * @copyright 2005-2019 Pronamic
 * @license GPL-3.0-or-later
 * @package Pronamic\WordPress\Pay\Gateways\DigiWallet
 */

namespace Pronamic\WordPress\Pay\Gateways\DigiWallet;

use Pronamic\WordPress\Pay\Core\Gateway as Core_Gateway;
use Pronamic\WordPress\Pay\Core\PaymentMethods;
use Pronamic\WordPress\Pay\Payments\Payment;

/**
 * Gateway
 *
 * @author Remco Tolsma
 * @version 1.0.0
 * @since 1.0.0
 */
class Gateway extends Core_Gateway {
	/**
	 * Client.
	 *
	 * @var Client
	 */
	private $client;

	/**
	 * Constructs and initializes an PayPal gateway.
	 *
	 * @param Config $config Config.
	 */
	public function __construct( Config $config ) {
		parent::__construct( $config );


	}

	/**
	 * Get supported payment methods
	 *
	 * @see Core_Gateway::get_supported_payment_methods()
	 * @return array<string>
	 */
	public function get_supported_payment_methods() {
		return array(
			PaymentMethods::BANCONTACT,
			PaymentMethods::IDEAL,
		);
	}

	/**
	 * Is payment method required to start transaction?
	 *
	 * @see Core_Gateway::payment_method_is_required()
	 * @return true
	 */
	public function payment_method_is_required() {
		return true;
	}

	/**
	 * Start.
	 *
	 * @param Payment $payment Payment.
	 * @return void
	 * @throws \InvalidArgumentException Throws exception if payment ID or currency is empty.
	 * @see Plugin::start()
	 */
	public function start( Payment $payment ) {
		$test = $payment->get_method();

		switch ( $payment->get_method() ) {
			case PaymentMethods::BANCONTACT:
				$url = 'https://transaction.digiwallet.nl/mrcash/start';

				$request = new BancontactStartRequest(
					$this->config->get_rtlo(),
					\strval( $payment->get_total_amount()->get_cents() ),
					$payment->get_description(),
					$payment->get_return_url(),
					'127.0.0.1'
				);

				break;
			case PaymentMethods::IDEAL:
				$url = 'https://transaction.digiwallet.nl/ideal/start';

				$request = new IDealStartRequest(
					$this->config->get_rtlo(),
					\strval( $payment->get_total_amount()->get_cents() ),
					$payment->get_description(),
					$payment->get_return_url()
				);

				break;
			default:
				throw new \Exception(
					\sprintf(
						'Unsupported payment method: %s.',
						$payment->get_method()
					)
				);
		}

		$request->set_test( $this->config->is_test() );

		$response = \Pronamic\WordPress\Http\Facades\Http::post(
			$url,
			array(
				'body' => $request->get_parameters(),
			)
		);

		$body = $response->body();

		$result_code = new ResultCode( \strtok( $body, ' ' ) );

		$message = \strtok( '' );

		if ( $result_code->is_error() ) {
			throw new Error( $result_code, $message );
		}

		$start_response = new StartResponse(
			$result_code,
			\strtok( $message, '|' ),
			\strtok( '' )
		);

		$payment->set_transaction_id( $start_response->get_transaction_number() );
		$payment->set_action_url( $start_response->get_bank_url() );
	}

	/**
	 * Update status of the specified payment.
	 *
	 * @param Payment $payment Payment.
	 * @return void
	 */
	public function update_status( Payment $payment ) {
		
	}
}
