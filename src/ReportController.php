<?php
/**
 * Report controller
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2022 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Gateways\DigiWallet
 */

namespace Pronamic\WordPress\Pay\Gateways\DigiWallet;

use Pronamic\WordPress\Pay\Plugin;

/**
 * Report controller
 *
 * @author  Remco Tolsma
 * @version 1.0.0
 * @since   1.0.0
 */
class ReportController {
	/**
	 * Setup.
	 *
	 * @return void
	 */
	public function setup() {
		\add_action( 'rest_api_init', array( $this, 'rest_api_init' ) );
	}

	/**
	 * REST API init.
	 *
	 * @link https://developer.wordpress.org/rest-api/extending-the-rest-api/adding-custom-endpoints/
	 * @link https://developer.wordpress.org/reference/hooks/rest_api_init/
	 * @return void
	 */
	public function rest_api_init() {
		\register_rest_route(
			Integration::REST_ROUTE_NAMESPACE,
			'/report',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'rest_api_digiwallet_report' ),
				'permission_callback' => '__return_true',
				'args'                => array(
					'trxid'      => array(
						'description' => \__( 'order number', 'pronamic_ideal' ),
						'type'        => 'string',
					),
					'amount'     => array(
						'description' => \__( 'amount in eurocents', 'pronamic_ideal' ),
						'type'        => 'string',
					),
					'idealtrxid' => array(
						'description' => \__( 'iDEAL order number', 'pronamic_ideal' ),
						'type'        => 'string',
					),
					'rtlo'       => array(
						'description' => \__( 'shop ID (layoutcode)', 'pronamic_ideal' ),
						'type'        => 'string',
					),
					'status'     => array(
						'description' => \__( 'status-code of the payment, see Check API', 'pronamic_ideal' ),
						'type'        => 'string',
					),
					'cname'      => array(
						'description' => \__( 'customer\'s name, if payment was successful', 'pronamic_ideal' ),
						'type'        => 'string',
					),
					'cbank'      => array(
						'description' => \__( 'customer\'s IBAN number, if payment was successful', 'pronamic_ideal' ),
						'type'        => 'string',
					),
					'cbic'       => array(
						'description' => \__( 'customer\'s BIC number, if payment was successful', 'pronamic_ideal' ),
						'type'        => 'string',
					),
				),
			)
		);
	}

	/**
	 * REST API DigiWallet report handler.
	 *
	 * @param \WP_REST_Request $request Request.
	 * @return object
	 * @throws \Exception Throws exception when something unexpected happens ;-).
	 */
	public function rest_api_digiwallet_report( \WP_REST_Request $request ) {
		$transaction_id = $request->get_param( 'trxid' );

		if ( empty( $transaction_id ) ) {
			return new \WP_Error(
				'pronamic_pay_digiwallet_transaction_id_empty',
				'Failed to process report request due to empty transaction ID.',
				array( 'status' => 500 )
			);
		}

		/**
		 * Search for payment.
		 */
		$payment = \get_pronamic_payment_by_transaction_id( $transaction_id );

		if ( null === $payment ) {
			return new \WP_Error(
				'pronamic_pay_digiwallet_payment_not_found',
				\sprintf(
					'Could not find payment with transaction ID: %s.',
					$transaction_id
				),
				array( 'status' => 500 )
			);
		}

		/**
		 * Webhook log payment.
		 *
		 * The `pronamic_pay_webhook_log_payment` action is triggered so the
		 * `wp-pay/core` library can hook into this and register the webhook
		 * call.
		 *
		 * @param Payment $payment Payment to log.
		 */
		\do_action( 'pronamic_pay_webhook_log_payment', $payment );

		/**
		 * Add note.
		 */
		$note = \sprintf(
			/* translators: %s: payment provider name */
			\__( 'Webhook requested by %s.', 'pronamic_ideal' ),
			\__( 'DigiWallet', 'pronamic_ideal' )
		);

		$payment->add_note( $note );

		/**
		 * Update payment.
		 *
		 * To secure that the status return is coming from DigiWallet you
		 * should always call to Check API. The output of the report URL
		 * will not be visible to the customer. The customer is redirected
		 * to the return- or cancel URL.
		 *
		 * @link https://www.digiwallet.nl/en/documentation/ideal#startapi
		 * @link https://www.digiwallet.nl/en/documentation/ideal#checkapi
		 */
		Plugin::update_payment( $payment, false );

		/**
		 * Return response.
		 */
		$rtlo = $request->get_param( 'rtlo' );

		return (object) array(
			'transaction_id' => $transaction_id,
			'rtlo'           => $rtlo,
		);
	}
}
