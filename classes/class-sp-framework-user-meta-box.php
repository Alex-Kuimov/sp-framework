<?php
class SP_Framework_User_Meta_Box extends SP_Framework_Main{
	
	use SP_Framework_Meta_Data_Field;
	
	function __construct(){
		$this->init();
	}

	private function init(){
		add_action('show_user_profile', function($user){
			$this->show($user);
		});

		add_action('edit_user_profile', function($user){
			$this->show($user);
		});

		add_action('personal_options_update', function($userID){
			$this->save($userID);
		});

		add_action('edit_user_profile_update', function($userID){
			$this->save($userID);
		});
	}

	private function show($user){
		$args = $this->args;

		if(isset($args['fields'])){

			$fields = $args['fields'];

			echo '<h3>'.$args['label'].'</h3>';

			echo '<table class="form-table">';

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
							$user->ID
						);
					}	
				}

			echo '</table>';	
		}
	}

	private function save($userID){
		if(!current_user_can('edit_user', $userID)){ 
	        return false; 
	    }

		$this->save_data($userID, 'user');
	}
}