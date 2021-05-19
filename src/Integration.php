<?php
/**
 * Integration
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2021 Pronamic
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
	 * Construct DigiWallet integration.
	 *
	 * @param array<string, array<string>> $args Arguments.
	 */
	public function __construct( $args = array() ) {
		$args = \wp_parse_args(
			$args,
			array(
				'id'            => 'digiwallet',
				'name'          => 'DigiWallet',
				'provider'      => 'digiwallet',
				'url'           => \__( 'https://www.digiwallet.nl/', 'pronamic_ideal' ),
				'product_url'   => \__( 'https://www.digiwallet.nl/', 'pronamic_ideal' ),
				'dashboard_url' => 'https://www.digiwallet.nl/nl/user/dashboard',
				'manual_url'    => \__(
					'https://www.pronamic.eu/manuals/using-paypal-pronamic-pay/',
					'pronamic_ideal'
				),
				'supports'      => array(),
			)
		);

		parent::__construct( $args );
	}

	/**
	 * Setup.
	 */
	public function setup() {
		\add_filter(
			'pronamic_gateway_configuration_display_value_' . $this->get_id(),
			array( $this, 'gateway_configuration_display_value' ),
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
		$fields = array();

		// Business Id.
		$fields[] = array(
			'section'  => 'general',
			'filter'   => \FILTER_SANITIZE_STRING,
			'meta_key' => '_pronamic_gateway_digiwallet_rtlo',
			'title'    => \_x( 'Shop ID (layoutcode)', 'digiwallet', 'pronamic_ideal' ),
			'type'     => 'text',
			'classes'  => array( 'regular-text', 'code' ),
		);

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
		$rtlo = $this->get_meta( $post_id, 'digiwallet_rtlo' );

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
