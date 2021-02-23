<?php
class SP_Framework_Taxonomy_Utility {

	public static function get_list( $data = null ) {
		//result array
		$result = array();

		//checking
		if ( $data ) {
			$values = array(
				'taxonomy'     => array( 'category' ),
				'meta_key'     => '',
				'orderby'      => 'id',
				'order'        => 'DESC',
				'hide_empty'   => false,
				'object_ids'   => null,
				'include'      => array(),
				'exclude'      => array(),
				'exclude_tree' => array(),
				'number'       => '',
				'fields'       => 'all',
				'count'        => false,
				'slug'         => '',
				'parent'       => '',
				'hierarchical' => true,
				'child_of'     => 0,
				'get'          => '',
				'name__like'   => '',
				'pad_counts'   => false,
				'offset'       => '',
				'search'       => '',
				'cache_domain' => 'core',
				'name'         => '',
				'childless'    => false,
				'meta_query'   => '',
			);

			//set custom value
			foreach ( $values as $key => $value ) {
				if ( array_key_exists( $key, $data ) ) {
					$values[ $key ] = $data[ $key ];
				}
			}

			$data = array(
				'taxonomy'     => $values['taxonomy'],
				'meta_key'     => $values['meta_key'],
				'orderby'      => $values['orderby'],
				'order'        => $values['order'],
				'hide_empty'   => $values['hide_empty'],
				'object_ids'   => $values['object_ids'],
				'include'      => $values['include'],
				'exclude'      => $values['exclude'],
				'exclude_tree' => $values['exclude_tree'],
				'number'       => $values['number'],
				'fields'       => $values['fields'],
				'count'        => $values['count'],
				'slug'         => $values['slug'],
				'parent'       => $values['parent'],
				'hierarchical' => $values['hierarchical'],
				'child_of'     => $values['child_of'],
				'get'          => $values['get'],
				'name__like'   => $values['name__like'],
				'pad_counts'   => $values['pad_counts'],
				'offset'       => $values['offset'],
				'search'       => $values['search'],
				'cache_domain' => $values['cache_domain'],
				'name'         => $values['name'],
				'childless'    => $values['childless'],
				'meta_query'   => $values['meta_query'],
			);

			$terms = get_terms( $data );

			$counter = 0;

			if ( count( $terms ) > 0 ) {
				foreach ( $terms as $term ) {
					//counter increment
					$counter++;

					$term_id = $term->term_id;

					$result[ $term_id ]['cnt']         = $counter;
					$result[ $term_id ]['id']          = $term_id;
					$result[ $term_id ]['parent']      = $term->parent;
					$result[ $term_id ]['name']        = $term->name;
					$result[ $term_id ]['title']       = $term->name;
					$result[ $term_id ]['description'] = $term->description;
					$result[ $term_id ]['url']         = get_term_link( $term_id );

				}
			}
		}

		//return result
		return $result;
	}

	public static function get_meta( $id, $name ) {
		if ( $id && $name ) {
			$name  = 'sp_' . $name;
			$value = get_term_meta( $id, $name, true );
		} else {
			$value = '';
		}

		return $value;
	}

	public static function get_current( $result = null ) {
		$term = get_queried_object();

		if ( isset( $term ) ) {
			$result['id']          = $term->term_id;
			$result['parent']      = $term->parent;
			$result['name']        = $term->name;
			$result['slug']        = $term->slug;
			$result['description'] = $term->description;
			$result['taxonomy']    = $term->taxonomy;
			$result['url']         = get_term_link( $term->term_id );
		}

		return $result;
	}

	public static function update_meta( $id, $name, $value ) {
		if ( $id && $name && $value ) {
			$name = 'sp_' . $name;
			update_term_meta( $id, $name, $value );
		}
	}
}
