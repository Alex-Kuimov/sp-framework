<?php
class SP_Framework_Admin_Utility {

	public static function get_meta( $name ) {
		if ( $name ) {
			$name  = 'sp_' . $name;
			$value = get_option( $name );
		} else {
			$value = '';
		}

		return $value;
	}

	public static function update_meta( $name, $value ) {
		if ( $name && $value ) {
			$name = 'sp_' . $name;
			update_option( $name, $value );
		}
	}

}
