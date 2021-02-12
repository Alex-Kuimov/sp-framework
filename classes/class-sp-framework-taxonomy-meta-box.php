<?php
class SP_Framework_Taxonomy_Meta_Box extends SP_Framework_Main{
	
	use SP_Framework_Meta_Data_Field;
	
	function __construct($taxonomy){
		$this->init($taxonomy);
	}

	private function init($taxonomy){
		add_action($taxonomy.'_add_form_fields', function($term){
			$this->show($term);
		}, 10, 2);

		add_action($taxonomy.'_edit_form_fields', function($term){
			$this->show($term);
		}, 10, 2);

		add_action('edited_'.$taxonomy, function($termID){
			$this->save($termID);
		}, 10, 2);

		add_action('create_'.$taxonomy, function($termID){
			$this->save($termID);
		}, 10, 2);
	}

	private function show($term){
		$args = $this->args;

		$termID = isset($term->term_id) ? $term->term_id : '';

		if(isset($args['fields'])){
			
			$fields = $args['fields'];

			foreach ($fields as $field) {
				if(	isset($field['type']) && 
					isset($field['name']) && 
					isset($field['label']) && 
					isset($field['caption']) && 
					isset($field['default']) && 
					isset($field['required'])
				){
					$this->add_field(
						$field['type'], 
						$field['name'], 
						$field['label'], 
						$field['caption'], 
						$field['default'], 
						$field['required'], 
						$termID
					);
				}	
			}
		}
	}

	private function save($termID){
		$this->save_data($termID, 'term');
	}

}