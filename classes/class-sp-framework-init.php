<?php
class SP_Framework_Init {

	public $ya_map_key;

	public function __construct() {
		$this->init();
	}

	private function js() {
		add_action(
			'admin_enqueue_scripts',
			function() {
				wp_register_script( 'sp_admin_script', plugins_url( '../assets/js/adminScript.js', __FILE__ ) );
				wp_enqueue_script( 'sp_admin_script', array( 'jquery', 'jquery-ui-sortable' ) );
				wp_enqueue_media();
			}
		);
	}

	private function css() {
		add_action(
			'admin_enqueue_scripts',
			function() {
				wp_register_style( 'sp_admin_style', plugins_url( '../assets/css/adminStyle.css', __FILE__ ), false, false, 'all' );
				wp_enqueue_style( 'sp_admin_style' );

			}
		);
	}

	public function ya_map( $key = null ) {
		$this->ya_map_key = $key;

		add_action(
			'admin_enqueue_scripts',
			function() {
				$key = $this->ya_map_key;
				if ( ! empty( $key ) ) {
					echo '<script src="https://api-maps.yandex.ru/2.1/?apikey=' . $key . '&lang=ru_RU"></script>';
				}
			}
		);
	}

	private function init() {
		$this->css();
		$this->js();
	}

}

$spInit = new SP_Framework_Init();
