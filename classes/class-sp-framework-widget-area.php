<?php
class SP_Framework_Widget_Area {

	public $config = array();

	function __construct() {
		$this->init();
	}

	public function create( $data ) {
		$this->config = $data;
	}

	private function register() {
		add_action(
			'widgets_init',
			function() {
				$data = $this->config;

				$values = array(
					'name'          => __( 'Custom widget', 'spf86' ),
					'id'            => 'spf86_widget',
					'description'   => __( 'Add widgets here', 'spf86' ),
					'before_title'  => '',
					'after_title'   => '',
					'before_widget' => '',
					'after_widget'  => '',
				);

				foreach ( $values as $key => $value ) {
					if ( array_key_exists( $key, $data ) ) {
						$values[ $key ] = $data[ $key ];
					}
				}

				register_sidebar( $values );
			}
		);
	}

	private function init() {
		$this->register();
	}
}
