<?php
class SP_Framework_User_Utility {

	public static function get_list( $data = null ) {
		$result = array();

		if ( $data ) {

			//default value
			$values = array(
				'role'         => '',
				'role__in'     => array(),
				'role__not_in' => array(),
				'meta_key'     => '',
				'meta_value'   => '',
				'meta_compare' => '',
				'meta_query'   => array(),
				'include'      => array(),
				'exclude'      => array(),
				'orderby'      => 'login',
				'order'        => 'ASC',
				'offset'       => '',
				'search'       => '',
				'number'       => '',
				'paged'        => 1,
				'count_total'  => false,
				'fields'       => 'all',
				'who'          => '',
				'date_query'   => array(),
			);

			//set custom value
			foreach ( $values as $key => $value ) {
				if ( array_key_exists( $key, $data ) ) {
					$values[ $key ] = $data[ $key ];
				}
			}

			//args array
			$args = array(
				'role'         => $values['role'],
				'role__in'     => $values['role__in'],
				'role__not_in' => $values['role__not_in'],
				'meta_key'     => $values['meta_key'],
				'meta_value'   => $values['meta_value'],
				'meta_compare' => $values['meta_compare'],
				'meta_query'   => $values['meta_query'],
				'include'      => $values['include'],
				'exclude'      => $values['exclude'],
				'orderby'      => $values['orderby'],
				'order'        => $values['order'],
				'offset'       => $values['offset'],
				'search'       => $values['search'],
				'number'       => $values['number'],
				'paged'        => $values['paged'],
				'count_total'  => $values['count_total'],
				'fields'       => $values['fields'],
				'who'          => $values['who'],
				'date_query'   => $values['date_query'],
			);

			$users = get_users( $args );

			if ( ! empty( $users ) ) {
				$index = 0;

				foreach ( $users as $user ) {

					$index++;
					$user_id = $user->ID;

					//data
					$result[ $user_id ]['cnt']        = $index;
					$result[ $user_id ]['id']         = $user_id;
					$result[ $user_id ]['login']      = $user->user_login;
					$result[ $user_id ]['nicename']   = $user->user_nicename;
					$result[ $user_id ]['email']      = $user->user_email;
					$result[ $user_id ]['url']        = $user->user_url;
					$result[ $user_id ]['registered'] = $user->user_registered;
					$result[ $user_id ]['status']     = $user->user_status;
					$result[ $user_id ]['name']       = $user->display_name;
				}
			}
		}

		return $result;
	}

	public static function get_meta( $id, $name ) {
		if ( $id && $name ) {
			$name  = 'sp_' . $name;
			$value = get_the_author_meta( $name, $id );
		} else {
			$value = '';
		}

		return $value;
	}

	public static function update_meta( $id, $name, $value ) {
		if ( $id && $name && $value ) {
			$name = 'sp_' . $name;
			update_user_meta( $id, $name, $value );
		}
	}

}
