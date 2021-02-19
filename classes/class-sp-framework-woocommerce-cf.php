<?php
class SP_Framework_Woocommerce_CF {

	public $args = array();

	public function __construct() {
		$this->un_set();
		$this->add();
		$this->show();
		$this->save();
	}

	public function init( $data = null ) {
		if ( $data ) {
			$this->args = $data;
		}
	}

	private function un_set() {
		add_filter(
			'woocommerce_checkout_fields',
			function( $fields ) {
				$data = $this->args;

				if ( isset( $data['action'] ) && $data['action'] == 'unset' ) {

					if ( isset( $data['fields'] ) ) {
						$data_fields = $data['fields'];
						foreach ( $data_fields as $field ) {

							$pos_billing  = strpos( $field, 'billing_' );
							$pos_shipping = strpos( $field, 'shipping_' );
							$pos_order    = strpos( $field, 'order_' );

							if ( $pos_billing !== false ) {
								$area = 'billing';
							}
							if ( $pos_shipping !== false ) {
								$area = 'shipping';
							}
							if ( $pos_order !== false ) {
								$area = 'order';
							}

							unset( $fields[ $area ][ $field ] );
						}
					}
				}

				return $fields;
			}
		);
	}

	private function add() {
		add_filter(
			'woocommerce_checkout_fields',
			function( $fields ) {
				$data = $this->args;
				if ( isset( $data['action'] ) && $data['action'] === 'add' ) {
					if ( isset( $data['fields'] ) ) {
						$data_fields = $data['fields'];
						foreach ( $data_fields as $field ) {
							if ( isset( $field['type'] ) && isset( $field['name'] ) && isset( $field['label'] ) && isset( $field['placeholder'] ) && isset( $field['priority'] ) && isset( $field['required'] ) ) {

								//name with pref
								$name = 'sp_' . $field['name'];

								$fields[ $field['area'] ][ $name ] = array(
									'type'        => $field['type'],
									'label'       => $field['label'],
									'placeholder' => $field['placeholder'],
									'required'    => $field['required'],
									'priority'    => $field['priority'],
								);
							}
						}
					}
				}
				return $fields;
			}
		);
	}

	private function show() {
		add_action(
			'woocommerce_admin_order_data_after_order_details',
			function( $order ) {

				$data     = $this->args;
				$order_id = $order->get_id();

				if ( isset( $data['action'] ) && $data['action'] == 'add' ) {
					if ( isset( $data['fields'] ) ) {
						$fields = $data['fields'];
						foreach ( $fields as $field ) {
							$meta  = 'sp_' . $field['name'];
							$value = get_post_meta( $order_id, $meta, true );

							echo '<p class="form-field form-field-wide wc-customer-user">';
							echo esc_html( '<strong>' . $field['label'] . ':</strong> ' . $value . '' );
							echo '</p>';
						}
					}
				}

			},
			10,
			1
		);
	}

	private function save() {
		add_action(
			'woocommerce_checkout_update_order_meta',
			function( $order_id ) {
				foreach ( $_POST as $key => $value ) {
					$pos = strpos( $key, 'sp_' );

					if ( $pos !== false ) {
						update_post_meta( $order_id, $key, $value );
					}
				}
			}
		);
	}
}
