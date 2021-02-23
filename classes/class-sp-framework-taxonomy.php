<?php
class SP_Framework_Taxonomy extends SP_Framework_Main {

	public function __construct() {
		$this->init();
	}

	private function register() {
		add_action(
			'init',
			function() {
				$data = $this->args;

				$default = array(
					'name'               => __( 'Custom taxonomy', 'spf86' ),
					'singular_name'      => __( 'Custom taxonomy', 'spf86' ),
					'menu_name'          => __( 'Custom taxonomy', 'spf86' ),
					'all_items'          => __( 'All', 'spf86' ),
					'edit_item'          => __( 'Edit', 'spf86' ),
					'view_item'          => __( 'View', 'spf86' ),
					'update_item'        => __( 'Update', 'spf86' ),
					'add_new_item'       => __( 'Add', 'spf86' ),
					'new_item_name'      => __( 'New', 'spf86' ),
					'label'              => __( 'Custom taxonomy', 'spf86' ),
					'public'             => true,
					'hierarchical'       => true,
					'show_ui'            => true,
					'show_in_menu'       => true,
					'show_in_nav_menus'  => true,
					'query_var'          => true,
					'rewrite'            => array(
						'slug'       => 'custom_taxonomy',
						'with_front' => true,
					),
					'show_admin_column'  => false,
					'show_in_rest'       => false,
					'rest_base'          => '',
					'show_in_quick_edit' => false,
					'slug'               => 'custom_taxonomy',
					'taxonomy'           => 'custom_taxonomy',
					'post_type'          => 'post',
				);

				foreach ( $default as $key => $value ) {
					if ( array_key_exists( $key, $data ) ) {
						$default[ $key ] = $data[ $key ];
					}
				}

				$labels = array(
					'name'          => $default['name'],
					'singular_name' => $default['singular_name'],
					'menu_name'     => $default['menu_name'],
					'all_items'     => $default['all_items'],
					'edit_item'     => $default['edit_item'],
					'view_item'     => $default['view_item'],
					'update_item'   => $default['update_item'],
					'add_new_item'  => $default['add_new_item'],
					'new_item_name' => $default['new_item_name'],
				);

				$args = array(
					'label'              => $default['label'],
					'labels'             => $labels,
					'public'             => $default['public'],
					'hierarchical'       => $default['hierarchical'],
					'show_ui'            => $default['show_ui'],
					'show_in_menu'       => $default['show_in_menu'],
					'show_in_nav_menus'  => $default['show_in_nav_menus'],
					'query_var'          => $default['query_var'],
					'rewrite'            => array(
						'slug'       => $default['slug'],
						'with_front' => true,
					),
					'show_admin_column'  => $default['show_admin_column'],
					'show_in_rest'       => $default['show_in_rest'],
					'rest_base'          => '',
					'show_in_quick_edit' => $default['show_in_quick_edit'],
				);

				if ( ! empty( $data['taxonomy'] ) && ! empty( $data['post_type'] ) ) {
					register_taxonomy( $default['taxonomy'], array( $default['post_type'] ), $args );
				} else {
					echo esc_html__( '$args[taxonomy] or $args[post_type] is empty!', 'spf86' );
					wp_die();
				}

			}
		);
	}

	private function init() {
		$this->register();
	}

}
