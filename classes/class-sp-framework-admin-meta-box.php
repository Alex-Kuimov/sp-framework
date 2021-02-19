<?php
class SP_Framework_Admin_Meta_Box extends SP_Framework_Main {

	use SP_Framework_Meta_Data_Field;

	function __construct() {
		$this->init();
	}

	private function init() {
		add_action(
			'admin_menu',
			function() {
				$data = $this->args;

				if ( ! empty( $data['type'] ) && $data['type'] === 'main' ) {
					$values = array(
						'page_title' => __( 'Custom settings page', 'spf86' ),
						'menu_title' => __( 'Custom settings page', 'spf86' ),
						'capability' => 'manage_options',
						'menu_slug'  => 'sp_menu',
						'icon'       => 'dashicons-admin-generic',
						'position'   => '1',
					);

					foreach ( $values as $key => $value ) {
						if ( array_key_exists( $key, $data ) ) {
							$values[ $key ] = $data[ $key ];
						}
					}

					add_menu_page(
						$values['page_title'],
						$values['menu_title'],
						$values['capability'],
						$values['menu_slug'],
						array( $this, 'show' ),
						$values['icon'],
						$values['position']
					);
				}

				if ( ! empty( $data['type'] ) && $data['type'] === 'sub' ) {
					$values = array(
						'parent_slug' => 'options-general.php',
						'page_title'  => __( 'Custom settings page', 'spf86' ),
						'menu_title'  => __( 'Custom settings page', 'spf86' ),
						'capability'  => 'manage_options',
						'menu_slug'   => 'sp_menu',
					);

					foreach ( $values as $key => $value ) {
						if ( array_key_exists( $key, $data ) ) {
							$values[ $key ] = $data[ $key ];
						}
					}

					add_submenu_page(
						$values['parent_slug'],
						$values['page_title'],
						$values['menu_title'],
						$values['capability'],
						$values['menu_slug'],
						array( $this, 'show' )
					);
				}
			}
		);
	}

	public function show() {
		$args = $this->args;

		if ( isset( $args['fields'] ) ) {

			$this->save();

			$fields = $args['fields'];

			echo '<h1>' . $args['page_title'] . '</h1>';
			echo '<form method="POST" action="">';

				//security_nonce
				$security_nonce = wp_create_nonce( 'sp_security_nonce' );

				echo '<table class="form-table">';

			foreach ( $fields as $field ) {
				if ( isset( $field['type'] ) &&
					isset( $field['name'] ) &&
					isset( $field['label'] ) &&
					isset( $field['caption'] ) &&
					isset( $field['default'] ) &&
					isset( $field['required'] )
				) {
					$this->add_field(
						$field['type'],
						$field['name'],
						$field['label'],
						$field['caption'],
						$field['default'],
						$field['required'],
						''
					);
				}
			}

				echo '</table>';

			echo '<input type="hidden" name="sp_save_data" value="' . $security_nonce . '">';
			echo '<p><input type="submit" value=" ' . __( 'Save', 'spf86' ) . '" class="button-primary"/></p>';
			echo '</form>';
		}
	}

	private function save() {
		if ( isset( $_POST['sp_save_data'] ) ) {
			if ( wp_verify_nonce( $_POST['sp_save_data'], 'sp_security_nonce' ) ) {
				$this->save_data( '', 'admin' );
			}
		}
	}
}
