<?php
class SP_Framework_Post_Type_Utility {

	public static function get_list( $data = null ) {
		//result array
		$result = array();

		//checking
		if ( $data ) {

			//default value
			$values = array(
				'numberposts'      => -1,
				'orderby'          => 'id',
				'order'            => 'DESC',
				'include'          => array(),
				'exclude'          => array(),
				'post_type'        => 'post',
				'post_status'      => 'publish',
				'suppress_filters' => true,
				'meta_key'         => '',
				'meta_value'       => '',
				'tax_query'        => array(),
				'meta_query'       => array(),
				'posts_per_page'   => '',
				'paged'            => '',
				's'                => '',
			);

			//set custom value
			foreach ( $values as $key => $value ) {
				if ( array_key_exists( $key, $data ) ) {
					$values[ $key ] = $data[ $key ];
				}
			}

			//args array
			$args = array(
				'numberposts'      => $values['numberposts'],
				'orderby'          => $values['orderby'],
				'order'            => $values['order'],
				'include'          => $values['include'],
				'exclude'          => $values['exclude'],
				'post_type'        => $values['post_type'],
				'post_status'      => $values['post_status'],
				'suppress_filters' => $values['suppress_filters'],
				'meta_key'         => $values['meta_key'],
				'meta_value'       => $values['meta_value'],
				'tax_query'        => $values['tax_query'],
				'meta_query'       => $values['meta_query'],
				'posts_per_page'   => $values['posts_per_page'],
				'paged'            => $values['paged'],
				's'                => $values['s'],
			);

			//get posts by args
			$posts   = get_posts( $args );
			$counter = 0;
			foreach ( $posts as $post ) {
				//counter increment
				$counter++;

				//current post id
				$post_id = $post->ID;

				//data
				$result[ $post_id ]['cnt']   = $counter;
				$result[ $post_id ]['id']    = $post_id;
				$result[ $post_id ]['title'] = get_the_title( $post_id );
				$result[ $post_id ]['url']   = get_permalink( $post_id );

			}
		}

		//return result
		return $result;
	}

	public static function get_content( $post_id = null ) {
		if ( ! empty( $post_id ) ) {
			$content = get_post_field( 'post_content', $post_id );
		} else {
			$content = '';
		}

		return $content;
	}

	public static function get_image( $post_id, $size ) {
		$img_id = get_post_thumbnail_id( $post_id );
		$image = wp_get_attachment_image_src( $img_id, $size );

		if ( $image[0] == '' ) {
			$result = plugins_url( '../assets/img/none.png', __FILE__ );
		} else {
			$result = esc_url( $image[0] );
		}

		return $result;
	}

	public static function get_meta( $post_id, $name ) {
		if ( $post_id && $name ) {
			$name  = 'sp_' . $name;
			$value = get_post_meta( $post_id, $name, true );
		} else {
			$value = '';
		}

		return $value;
	}

	public static function update_meta( $id, $name, $value ) {
		if ( $id && $name && $value ) {
			$name = 'sp_' . $name;
			update_post_meta( $id, $name, $value );
		}
	}

	public static function get_pagination( $wp_query = null, $args = null ) {
		if ( ! empty( $wp_query ) ) {

			if ( isset( $wp_query->query['paged'] ) ) {
				$current_paged = $wp_query->query['paged'];
			} else {
				$current_paged = 1;
			}

			if ( isset( $args['posts_per_page'] ) ) {
				$posts_per_page = $args['posts_per_page'];
			} else {
				$posts_per_page = get_option( 'posts_per_page' );
			}

			if ( isset( $wp_query->queried_object->taxonomy ) ) {
				$current_term_id  = $wp_query->queried_object->term_id;
				$current_taxonomy = $wp_query->queried_object->taxonomy;
				$pagination_link  = get_term_link( $current_term_id, $current_taxonomy );
				$total_posts      = $wp_query->found_posts;
			} else {

				if ( isset( $args['count_posts'] ) ) {
					$total_posts = $args['count_posts'];
				} else {
					$total_posts = $wp_query->found_posts;
				}

				if ( isset( $args['page'] ) ) {
					$pagination_link = get_home_url() . '/' . $args['page'] . '/';
				} else {
					$pagination_link = get_home_url() . '/';
				}
			}

			$pagination_count = ceil( $total_posts / $posts_per_page );

			$param_get = '';
			if ( isset( $_GET ) && ! empty( $_GET ) ) {
				$array_get  = $_GET;
				$param_get .= '?';
				foreach ( $array_get  as $key => $value ) {
					$param_get .= $key . '=' . $value . '&';
				}
			}

			if ( isset( $args['range'] ) ) {
				$range         = (int) $args['range'] - 1;
				$range_default = (int) $args['range'] - 1;
			} else {
				$range         = 5;
				$range_default = 5;
			}

			if ( isset( $args['start_link_title'] ) ) {
				if ( $current_paged == 1 ) {
					$start_link = '<span>' . $args['start_link_title'] . '</span>';
				} else {
					$start_link = '<a href="' . $pagination_link . '' . $param_get . '">' . $args['start_link_title'] . '</a>';
				}
			} else {
				$start_link = '';
			}

			if ( isset( $args['start_link_title'] ) ) {
				if ( $current_paged == $pagination_count ) {
					$end_link = '<span>' . $args['end_link_title'] . '</span>';
				} else {
					$end_link = '<a href="' . $pagination_link . 'page/' . $pagination_count . '/' . $param_get . '">' . $args['end_link_title'] . '</a>';
				}
			} else {
				$end_link = '';
			}

			$result = '';

			if ( $total_posts > $posts_per_page ) {

				if ( isset( $args['wrapper_start'] ) ) {
					$result .= $args['wrapper_start'];
				}

				$result .= $start_link;

				for ( $i = 1; $i <= $pagination_count; $i++ ) {

					if ( $current_paged == 1 ) {
						$range = $range_default + 1;
					}

					if ( $i <= $range + $current_paged - 1 ) {

						if ( $range + $current_paged <= $pagination_count ) {

							if ( $i >= $current_paged - 1 ) {

								if ( $i == $current_paged ) {
									$result .= '<span>' . $i . '</span>';
								} else {
									$result .= '<a href="' . $pagination_link . 'page/' . $i . '/' . $param_get . '">' . $i . '</a>';
								}
							}
						} else {

							if ( $i >= $pagination_count - $range ) {

								if ( $i == $current_paged ) {
									$result .= '<span>' . $i . '</span>';
								} else {
									$result .= '<a href="' . $pagination_link . 'page/' . $i . '/' . $param_get . '">' . $i . '</a>';
								}
							}
						}
					}
				}

				$result .= $end_link;

				if ( isset( $args['total'] ) && isset( $args['total'] ) == 'y' ) {

					if ( isset( $args['wrapper_total_start'] ) ) {
						$result .= $args['wrapper_total_start'];
					}

					$result .= '<span>' . $current_paged;

					if ( isset( $args['total_separator'] ) ) {
						$result .= $args['total_separator'];
					}

					$result .= $pagination_count . '</span>';

					if ( isset( $args['wrapper_total_end'] ) ) {
						$result .= $args['wrapper_total_end'];
					}
				}

				if ( isset( $args['wrapper_end'] ) ) {
					$result .= $args['wrapper_end'];
				}
			}

			return $result;
		}
	}

}
