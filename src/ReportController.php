<?php
/**
 * Report controller
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2021 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Gateways\DigiWallet
 */

namespace Pronamic\WordPress\Pay\Gateways\DigiWallet;

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
		return true;
	}
}
