<?php
abstract class SP_Framework_AJAX {

	public function __construct( $actionName ) {
		$this->init_hooks( $actionName );
	}

	public function init_hooks( $actionName ) {
		add_action( 'wp_ajax_' . $actionName, array( $this, 'ajax_action' ) );
		add_action( 'wp_ajax_nopriv_' . $actionName, array( $this, 'ajax_action_nopriv' ) );
	}

	public function ajax_action_nopriv() {
		$this->ajax_action();
	}

	abstract public function ajax_action();

}
