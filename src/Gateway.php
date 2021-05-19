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
use Pronamic\WordPress\Pay\Payments\PaymentStatus;

/**
 * Gateway
 *
 * @author Remco Tolsma
 * @version 1.0.0
 * @since 1.0.0
 */
class Gateway extends Core_Gateway {
	/**
	 * Constructs and initializes an PayPal gateway.
	 *
	 * @param Config $config Config.
	 */
	public function __construct( Config $config ) {
		parent::__construct( $config );

		$this->set_method( self::METHOD_HTTP_REDIRECT );
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
	 * @throws \Exception Throws eception on remote request issues.
	 * @throws Error Throws error when DigiWallet returns an error.
	 * @see Plugin::start()
	 */
	public function start( Payment $payment ) {
		if ( ! $this->config instanceof Config ) {
			throw new \Exception( 'No DigiWallet configuration.' );
		}

		switch ( $payment->get_method() ) {
			case PaymentMethods::BANCONTACT:
				$url = 'https://transaction.digiwallet.nl/mrcash/start';

				$request = new BancontactStartRequest(
					$this->config->get_rtlo(),
					\strval( $payment->get_total_amount()->get_minor_units() ),
					\strval( $payment->get_description() ),
					$payment->get_return_url(),
					'127.0.0.1'
				);

				break;
			case PaymentMethods::IDEAL:
				$url = 'https://transaction.digiwallet.nl/ideal/start';

				$request = new IDealStartRequest(
					$this->config->get_rtlo(),
					\strval( $payment->get_total_amount()->get_minor_units() ),
					\strval( $payment->get_description() ),
					$payment->get_return_url()
				);

				break;
			default:
				throw new \Exception(
					\sprintf(
						'Unsupported payment method: %s.',
						\strval( $payment->get_method() )
					)
				);
		}

		$request->set_test( $this->config->is_test() );
		$request->set_report_url( \rest_url( Integration::REST_ROUTE_NAMESPACE . '/report' ) );

		$response = \Pronamic\WordPress\Http\Facades\Http::post(
			$url,
			array(
				'body' => $request->get_parameters(),
			)
		);

		$body = $response->body();

		$result_code = new ResultCode( \strval( \strtok( $body, ' ' ) ) );

		$message = \strval( \strtok( '' ) );

		if ( $result_code->is_error() ) {
			throw new Error( $result_code, $message );
		}

		$start_response = new StartResponse(
			$result_code,
			\strval( \strtok( $message, '|' ) ),
			\strval( \strtok( '' ) )
		);

		$payment->set_transaction_id( $start_response->get_transaction_number() );
		$payment->set_action_url( $start_response->get_bank_url() );
	}

	/**
	 * Update status of the specified payment.
	 *
	 * @param Payment $payment Payment.
	 * @return void
	 * @throws \Exception Throws eception on remote request issues.
	 * @throws Error Throws error on unknown internal error.
	 */
	public function update_status( Payment $payment ) {
		if ( ! $this->config instanceof Config ) {
			throw new \Exception( 'No DigiWallet configuration.' );
		}

		$transaction_id = $payment->get_transaction_id();

		if ( null === $transaction_id ) {
			throw new \Exception( 'No transaction ID.' );
		}

		switch ( $payment->get_method() ) {
			/**
			 * Payment method Bancontact.
			 *
			 * @link https://www.digiwallet.nl/en/documentation/paymethods/bancontact#checkapi
			 */
			case PaymentMethods::BANCONTACT:
				$url = 'https://transaction.digiwallet.nl/mrcash/check';

				break;
			/**
			 * Payment method iDEAL.
			 *
			 * @link https://www.digiwallet.nl/en/documentation/ideal#checkapi
			 */
			case PaymentMethods::IDEAL:
				$url = 'https://transaction.digiwallet.nl/ideal/check';

				break;
			default:
				throw new \Exception(
					\sprintf(
						'Unsupported payment method: %s.',
						\strval( $payment->get_method() )
					)
				);
		}

		$request = new CheckRequest(
			$this->config->get_rtlo(),
			$transaction_id
		);

		$response = \Pronamic\WordPress\Http\Facades\Http::post(
			$url,
			array(
				'body' => $request->get_parameters(),
			)
		);

		$body = $response->body();

		$result_code = new ResultCode( \strval( \strtok( $body, ' ' ) ) );

		$message = \strval( \strtok( '' ) );

		switch ( $result_code ) {
			case '000000':
				$payment->set_status( PaymentStatus::SUCCESS );

				break;
			case 'DW_SE_0020':
				// Transaction has not been completed, try again later.
				$payment->set_status( PaymentStatus::OPEN );

				break;
			case 'DW_SE_0021':
				// Transaction has been cancelled.
				$payment->set_status( PaymentStatus::CANCELLED );

				break;
			case 'DW_SE_0022':
				// Transaction has expired.
				$payment->set_status( PaymentStatus::EXPIRED );

				break;
			case 'DW_SE_0023':
				// Transaction could not be processed.
				$payment->set_status( PaymentStatus::FAILURE );

				break;
			case 'DW_SE_0028':
				// Transaction already checked at `datetime`.

				break;
			case 'DW_XE_0003':
				// phpcs:ignore Squiz.PHP.CommentedOutCode.Found
				// Validation failed, details: `JSON-encoded array`.
				$payment->set_status( PaymentStatus::FAILURE );

				break;
			case 'DW_IE_0002':
				// Maximum retries at acquirer bank exceeded for primary and fallback.
				$payment->set_status( PaymentStatus::FAILURE );

				break;
			case 'DW_IE_0006':
				// System is busy, please retry later.

				break;
			case 'DW_IE_0001':
				// Unknown internal error.
				throw new Error( $result_code, $message );
		}
	}
}
