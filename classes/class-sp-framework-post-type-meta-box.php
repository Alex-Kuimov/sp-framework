<?php
class SP_Framework_Post_Type_Meta_Box extends SP_Framework_Main {

	use SP_Framework_Meta_Data_Field;

	function __construct() {
		$this->init();
	}

	private function init() {

		add_action(
			'add_meta_boxes',
			function() {
				if ( isset( $this->args['name'] ) && isset( $this->args['label'] ) && isset( $this->args['post_type'] ) ) {
					add_meta_box(
						'sp_' . $this->args['name'],
						$this->args['label'],
						array( $this, 'show' ), // public!
						$this->args['post_type'],
						'normal',
						'default'
					);
				} else {
					echo esc_html__( '$args[name], $args[label] or $args[post_type] is empty!', 'spf86' );
					wp_die();
				}
			}
		);

		$this->save();
	}

	public function show( $post ) {
		$args = $this->args;

		if ( isset( $args['fields'] ) ) {

			$fields = $args['fields'];

			echo '<table class="form-table">';
			foreach ( $fields as $field ) {
				if ( isset( $field['type'] ) &&
					isset( $field['name'] ) &&
					isset( $field['label'] ) &&
					isset( $field['caption'] ) &&
					isset( $field['default'] ) &&
					isset( $field['required'] )
				) {
					$this->add_field(
						$field['type'],
						$field['name'],
						$field['label'],
						$field['caption'],
						$field['default'],
						$field['required'],
						$post->ID
					);
				}
			}
			echo '</table>';

		}
	}

	private function save() {
		add_action(
			'save_post',
			function( $postID ) {
				$postType = get_post_type( $postID );

				if ( isset( $this->args['post_type'] ) ) {
					if ( $postType == strtolower( $this->args['post_type'] ) ) {
						if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
							return;
						}

						$this->save_data( $postID, 'post' );

					}
				}
			}
		);
	}

}
