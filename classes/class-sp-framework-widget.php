<?php
abstract class SP_Framework_Widget_ABS extends WP_Widget {

	//show widget(frontend)
	function widget( $args, $data ) {
		$this->get( $args, $data );
	}

	//show widget(backend)
	function form( $data ) {
		$this->create( $data );
	}

	//update new data
	function update( $new_data, $old_data ) {
		$data = array();

		foreach ( $new_data as $key => $value ) {
			$sp_meta_data = $this->validate( '3000', $value );
			$sp_meta_data = sanitize_text_field( $sp_meta_data );
			$data[ $key ] = $sp_meta_data;
		}

		return $data;
	}

	//validate data
	private function validate( $count, $data ) {
		if ( strlen( $data ) > $count ) {
			$data_len = strlen( $data ) - $count;
			$data     = substr( $data, 0, -$data_len );
		}
		return $data;
	}

	//fields function (input, select, etc)
	public function add_field( $type, $label, $caption, $values, $id ) {
		if ( $type == 'text' ) {
			echo '<p><label for="' . $this->get_field_id( $id ) . '">' . $label . ':</label><br>';
			echo '<input type="text" id="' . $this->get_field_id( $id ) . '" name="' . $this->get_field_name( $id ) . '" value="' . esc_attr( $values ) . '"></p>';
		}

		if ( $type == 'number' ) {
			echo '<p><label for="' . $this->get_field_id( $id ) . '">' . $label . ':</label><br>';
			echo '<input type="number" class="spnumber" id="' . $this->get_field_id( $id ) . '" name="' . $this->get_field_name( $id ) . '" value="' . esc_attr( $values ) . '"></p>';
		}

		if ( $type == 'select' ) {
			echo '<p><label for="' . $this->get_field_id( $id ) . '">' . $label . ':</label><br>';
			echo '<select class="widefat" id="' . $this->get_field_id( $id ) . '" name="' . $this->get_field_name( $id ) . '">';

			$i = 0;
			foreach ( $values as $val ) {

				if ( $i == 0 ) {
					foreach ( $values as $fval ) {
						if ( $fval['key'] == $val['key'] && ! empty( $fval['value'] ) ) {
							echo '<option value="' . $fval['key'] . '">' . $fval['value'] . '</option>';
						}
					}
				}

				$i++;

				if ( ! empty( $val['value'] ) ) {
					echo '<option value="' . $val['key'] . '">' . $val['value'] . '</option>';
				}

				if ( $i == 1 ) {
					echo '<option disabled>-------</option>';
				}
			}

			echo '</select>';
		}

		if ( ! empty( $caption ) ) {
			echo '<p>' . $caption . '</p>';
		}
	}

	public function default( $data, $key ) {
		if ( isset( $data[ $key ] ) ) {
			return $data[ $key ];
		} else {
			return '';
		}
	}

	function register() {
		register_widget( get_class( $this ) );
	}

	abstract public function get( $args, $data);
	abstract public function create( $data);

}
