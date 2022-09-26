<?php
/**
 * Gateway
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2022 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Gateways\DigiWallet
 */

namespace Pronamic\WordPress\Pay\Gateways\DigiWallet;

use Pronamic\WordPress\Http\Facades\Http;
use Pronamic\WordPress\Pay\Core\Gateway as Core_Gateway;
use Pronamic\WordPress\Pay\Core\PaymentMethod;
use Pronamic\WordPress\Pay\Core\PaymentMethods;
use Pronamic\WordPress\Pay\Fields\CachedCallbackOptions;
use Pronamic\WordPress\Pay\Fields\IDealIssuerSelectField;
use Pronamic\WordPress\Pay\Fields\SelectFieldOption;
use Pronamic\WordPress\Pay\Payments\Payment;
use Pronamic\WordPress\Pay\Payments\PaymentStatus;

/**
 * Gateway
 *
 * @author  Remco Tolsma
 * @version 1.0.0
 * @since   1.0.0
 */
class Gateway extends Core_Gateway {
	/**
	 * Config.
	 *
	 * @var Config
	 */
	private $config;

	/**
	 * Constructs and initializes an DigiWallet gateway.
	 *
	 * @param Config $config Config.
	 */
	public function __construct( Config $config ) {
		parent::__construct();

		$this->config = $config;

		$this->set_method( self::METHOD_HTTP_REDIRECT );

		$this->set_mode( $this->config->is_test() ? 'test' : 'live' );

		// Supported features.
		$this->supports = [
			'payment_status_request',
			'webhook',
			'webhook_log',
			'webhook_no_config',
		];

		// Methods.
		$ideal_payment_method = new PaymentMethod( PaymentMethods::IDEAL );

		$ideal_issuer_field = new IDealIssuerSelectField( 'ideal-issuer' );

		$ideal_issuer_field->set_options(
			new CachedCallbackOptions(
				function() {
					return $this->get_ideal_issuers();
				},
				'pronamic_pay_ideal_issuers_' . \md5( \wp_json_encode( $config ) )
			) 
		);

		$ideal_payment_method->add_field( $ideal_issuer_field );

		$this->register_payment_method( new PaymentMethod( PaymentMethods::BANCONTACT ) );
		$this->register_payment_method( $ideal_payment_method );
		$this->register_payment_method( new PaymentMethod( PaymentMethods::PAYPAL ) );
	}

	/**
	 * Get iDEAL issuers.
	 *
	 * @return iterable<SelectFieldOption|SelectFieldOptionGroup>
	 */
	private function get_ideal_issuers() {
		/**
		 * Get banklist.
		 *
		 * @link https://www.digiwallet.nl/en/documentation/ideal#banklist
		 */
		$url = \add_query_arg(
			[
				'ver'    => '4',
				'format' => 'xml',
			],
			'https://transaction.digiwallet.nl/ideal/getissuers'
		);

		/**
		 * Request.
		 */
		$response = Http::get( $url );

		$simplexml = $response->simplexml();

		$options = [];

		foreach ( $simplexml->issuer as $issuer ) {
			$options[] = new SelectFieldOption( (string) $issuer['id'], (string) $issuer );
		}

		return $options;
	}

	/**
	 * Start.
	 *
	 * @param Payment $payment Payment.
	 * @return void
	 * @throws \Exception Throws exception on remote request issues.
	 * @throws Error Throws error when DigiWallet returns an error.
	 * @see Plugin::start()
	 */
	public function start( Payment $payment ) {
		switch ( $payment->get_payment_method() ) {
			case PaymentMethods::BANCONTACT:
				$url = 'https://transaction.digiwallet.nl/mrcash/start';

				// User IP address.
				$user_ip = '';

				$customer = $payment->get_customer();

				if ( null !== $customer ) {
					$ip_address = $customer->get_ip_address();

					if ( null !== $ip_address ) {
						$user_ip = $ip_address;
					}
				}

				$request = new BancontactStartRequest(
					$this->config->get_rtlo(),
					\strval( $payment->get_total_amount()->get_minor_units() ),
					\strval( $payment->get_description() ),
					$payment->get_return_url(),
					$user_ip
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

				$request->set_bank( $payment->get_meta( 'issuer' ) );

				break;
			case PaymentMethods::PAYPAL:
				$url = 'https://transaction.digiwallet.nl/paypal/start';

				$request = new PayPalStartRequest(
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
						\strval( $payment->get_payment_method() )
					)
				);
		}

		$request->set_test( $this->config->is_test() );

		/**
		 * Report URL.
		 */
		$report_url = \rest_url( Integration::REST_ROUTE_NAMESPACE . '/report' );

		/**
		 * Filters the DigiWallet report URL.
		 *
		 * If you want to debug the DigiWallet report URL you can use this filter
		 * to override the report URL. You could for example use a service like
		 * https://webhook.site/ to inspect the report requests from DigiWallet.
		 *
		 * @param string $report_url DigiWallet report URL.
		 */
		$report_url = \apply_filters( 'pronamic_pay_digiwallet_report_url', $report_url );

		$request->set_report_url( $report_url );

		$response = Http::post(
			$url,
			[
				'body' => $request->get_parameters(),
			]
		);

		$body = $response->body();

		$result_code = new ResultCode( \strval( \strtok( $body, ' ' ) ) );

		if ( $result_code->is_error() ) {
			throw new Error( $result_code, $body );
		}

		$start_response = StartResponse::from_response_body( $body );

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
		$transaction_id = $payment->get_transaction_id();

		if ( null === $transaction_id ) {
			throw new \Exception( 'No transaction ID.' );
		}

		switch ( $payment->get_payment_method() ) {
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
			/**
			 * Payment method PayPal.
			 *
			 * @link https://www.digiwallet.nl/en/documentation/paypal#checkapi
			 */
			case PaymentMethods::PAYPAL:
				$url = 'https://transaction.digiwallet.nl/paypal/check';

				break;
			default:
				throw new \Exception(
					\sprintf(
						'Unsupported payment method: %s.',
						\strval( $payment->get_payment_method() )
					)
				);
		}

		$request = new CheckRequest(
			$this->config->get_rtlo(),
			$transaction_id
		);

		$response = Http::post(
			$url,
			[
				'body' => $request->get_parameters(),
			]
		);

		$body = $response->body();

		$result_code = new ResultCode( \strval( \strtok( $body, ' ' ) ) );

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
				$message = \strval( \strtok( '' ) );

				throw new Error( $result_code, $message );
		}
	}
}
