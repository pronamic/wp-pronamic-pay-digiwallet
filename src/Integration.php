<?php
/**
 * Integration
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2022 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Gateways\DigiWallet
 */

namespace Pronamic\WordPress\Pay\Gateways\DigiWallet;

use Pronamic\WordPress\Pay\AbstractGatewayIntegration;

/**
 * Integration
 *
 * @author  Remco Tolsma
 * @version 1.0.0
 * @since   1.0.0
 */
class Integration extends AbstractGatewayIntegration {
	/**
	 * REST route namespace.
	 *
	 * @var string
	 */
	const REST_ROUTE_NAMESPACE = 'pronamic-pay/digiwallet/v1';

	/**
	 * Meta key RTLO.
	 * 
	 * @var string
	 */
	private $meta_key_rtlo;

	/**
	 * Construct DigiWallet integration.
	 *
	 * @param array<string, array<string>> $args Arguments.
	 */
	public function __construct( $args = [] ) {
		$args = \wp_parse_args(
			$args,
			[
				'id'            => 'digiwallet',
				'name'          => 'DigiWallet',
				'provider'      => 'digiwallet',
				'url'           => \__( 'https://www.digiwallet.nl/', 'pronamic_ideal' ),
				'product_url'   => \__( 'https://www.digiwallet.nl/', 'pronamic_ideal' ),
				'dashboard_url' => 'https://www.digiwallet.nl/nl/user/dashboard',
				'manual_url'    => \__(
					'https://www.pronamicpay.com/en/manuals/how-to-connect-digiwallet-to-wordpress-with-pronamic-pay/',
					'pronamic_ideal'
				),
				'supports'      => [
					'payment_status_request',
					'webhook',
					'webhook_log',
					'webhook_no_config',
				],
				'meta_key_rtlo' => 'digiwallet_rtlo',
			]
		);

		parent::__construct( $args );

		$this->meta_key_rtlo = $args['meta_key_rtlo'];
	}

	/**
	 * Setup.
	 */
	public function setup() {
		\add_filter(
			'pronamic_gateway_configuration_display_value_' . $this->get_id(),
			[ $this, 'gateway_configuration_display_value' ],
			10,
			2
		);

		// Report controller.
		$report_controller = new ReportController();

		$report_controller->setup();
	}

	/**
	 * Gateway configuration display value.
	 *
	 * @param string $display_value Display value.
	 * @param int    $post_id       Gateway configuration post ID.
	 * @return string
	 */
	public function gateway_configuration_display_value( $display_value, $post_id ) {
		$config = $this->get_config( $post_id );

		$display_value = $config->get_rtlo();

		return $display_value;
	}

	/**
	 * Get settings fields.
	 *
	 * @return array<int, array<string, callable|int|string|bool|array<int|string,int|string>>>
	 */
	public function get_settings_fields() {
		$fields = [];

		// Business Id.
		$fields[] = [
			'section'  => 'general',
			'meta_key' => '_pronamic_gateway_' . $this->meta_key_rtlo,
			'title'    => \_x( 'Shop ID (layoutcode)', 'digiwallet', 'pronamic_ideal' ),
			'type'     => 'text',
			'classes'  => [ 'regular-text', 'code' ],
			'required' => true,
		];

		// Return fields.
		return $fields;
	}

	/**
	 * Get configuration by post ID.
	 *
	 * @param int $post_id Post ID.
	 * @return Config
	 */
	public function get_config( $post_id ) {
		$rtlo = $this->get_meta( $post_id, $this->meta_key_rtlo );

		$config = new Config( $rtlo );

		$mode = $this->get_meta( $post_id, 'mode' );

		if ( 'test' === $mode ) {
			$config->set_test( true );
		}

		return $config;
	}

	/**
	 * Get gateway.
	 *
	 * @param int $post_id Post ID.
	 * @return Gateway
	 */
	public function get_gateway( $post_id ) {
		$config = $this->get_config( $post_id );

		return new Gateway( $config );
	}
}
