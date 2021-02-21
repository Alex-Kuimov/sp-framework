<?php
class SP_Framework_Post_Type extends SP_Framework_Main {

	public function __construct() {
		$this->init();
	}

	private function register() {
		add_action(
			'init',
			function() {
				$data = $this->args;

				$default = array(
					'name'                => 'custom_post_type',
					'all_items'           => __( 'All', 'spf86' ),
					'add_new'             => __( 'Add New ', 'spf86' ),
					'add_new_item'        => __( 'Add', 'spf86' ),
					'edit_item'           => __( 'Edit', 'spf86' ),
					'new_item'            => __( 'New', 'spf86' ),
					'view_item'           => __( 'View', 'spf86' ),
					'view_items'          => __( 'View', 'spf86' ),
					'search_items'        => __( 'Search', 'spf86' ),
					'not_found'           => __( 'Not found', 'spf86' ),
					'not_found_in_trash'  => __( 'Not found in trash', 'spf86' ),
					'label'               => __( 'Custom Post Type', 'spf86' ),
					'description'         => '',
					'public'              => false,
					'publicly_queryable'  => true,
					'show_ui'             => true,
					'show_in_rest'        => false,
					'rest_base'           => '',
					'has_archive'         => false,
					'show_in_menu'        => true,
					'exclude_from_search' => true,
					'capability_type'     => 'post',
					'map_meta_cap'        => true,
					'hierarchical'        => false,
					'slug'                => 'custom_post_type',
					'query_var'           => true,
					'supports'            => array( 'title' ),
					'menu_icon'           => 'dashicons-menu',
				);

				foreach ( $default as $key => $value ) {
					if ( array_key_exists( $key, $data ) ) {
						$default[ $key ] = $data[ $key ];
					}
				}

				$labels = array(
					'name'               => $default['label'],
					'singular_name'      => $default['label'],
					'all_items'          => $default['all_items'],
					'add_new'            => $default['add_new'],
					'add_new_item'       => $default['add_new_item'],
					'edit_item'          => $default['edit_item'],
					'new_item'           => $default['new_item'],
					'view_item'          => $default['view_item'],
					'view_items'         => $default['view_items'],
					'search_items'       => $default['search_items'],
					'not_found'          => $default['not_found'],
					'not_found_in_trash' => $default['not_found_in_trash'],
				);

				$args = array(
					'label'               => $default['label'],
					'labels'              => $labels,
					'description'         => '',
					'public'              => $default['public'],
					'publicly_queryable'  => $default['publicly_queryable'],
					'show_ui'             => $default['show_ui'],
					'show_in_rest'        => $default['show_in_rest'],
					'rest_base'           => '',
					'has_archive'         => $default['has_archive'],
					'show_in_menu'        => $default['show_in_menu'],
					'exclude_from_search' => $default['exclude_from_search'],
					'capability_type'     => 'post',
					'map_meta_cap'        => $default['map_meta_cap'],
					'hierarchical'        => $default['hierarchical'],
					'rewrite'             => array(
						'slug'       => $default['slug'],
						'with_front' => true,
					),
					'query_var'           => $default['query_var'],
					'supports'            => $default['supports'],
					'menu_icon'           => $default['menu_icon'],
				);

				if ( ! empty( $data['name'] ) ) {
					register_post_type( $default['name'], $args );
				} else {
					echo esc_html__( '$args[name] is empty!', 'spf86' );
					wp_die();
				}
			}
		);
	}

	private function redirect() {
		add_filter(
			'template_include',
			function( $template ) {
				$data = $this->args;

				if ( isset( $data['hidden'] ) && $data['hidden'] == 'y' ) {
					$current_id       = get_the_ID();
					$current_post_type = get_post_type( $current_id );

					if ( $current_post_type == $data['name'] ) {
						wp_redirect( get_home_url() . '/404.php' );
						exit;
					}
				}

				return $template;
			}
		);
	}

	private function init() {
		$this->register();
		$this->redirect();
	}

}
