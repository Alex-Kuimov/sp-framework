<?php
abstract class SP_Framework_AJAX {

	public function __construct( $action_name ) {
		$this->init_hooks( $action_name );
	}

	public function init_hooks( $action_name ) {
		add_action( 'wp_ajax_' . $action_name, array( $this, 'ajax_action' ) );
		add_action( 'wp_ajax_nopriv_' . $action_name, array( $this, 'ajax_action_nopriv' ) );
	}

	public function ajax_action_nopriv() {
		$this->ajax_action();
	}

	abstract public function ajax_action();

}
