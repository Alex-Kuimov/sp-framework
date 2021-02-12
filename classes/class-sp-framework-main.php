<?php
class SP_Framework_Main{

	public $args = array();
	
	public function create($args=null){
		$dataType = gettype($args);

		if($dataType != 'array'){
			echo esc_html__('$args is not array!', 'spf86');
			wp_die();
		}

		if(empty($args)){
			echo esc_html__('$args is empty!', 'spf86');
			wp_die();
		}

		$this->args = $args;
	}

	private function validate($count, $data){
		if(!is_array($data)){
			if(strlen($data)>$count){
				$dataLen = strlen($data)-$count;
				$data = substr($data, 0, -$dataLen);
			}
		}	
		return $data;
	}

	protected function save_data($id=null, $type=null){

		foreach($_POST as $key => $value) {
			$pos 	= strpos($key, 'sp_');
			$posImg = strpos($key, 'sp_img_');

			if($pos !== false) {
				if($this->args['validate'] == 'y'){
					$value = $this->validate(3000, $value);
				}	

				if($posImg === false) {
					if($this->args['sanitize'] == 'y'){
						$value = sanitize_text_field($value);
					}

					$value = trim($value);
				}

				if($type == 'post'){	
					update_post_meta($id, $key, $value);
				}

				if($type == 'term'){	
					update_term_meta($id, $key, $value);
				}

				if($type == 'user'){	
					update_user_meta($id, $key, $value);
				}

				if($type == 'admin' && $key != 'sp_save_data'){
					update_option($key, $value);
				}
			} 
		}
	}
}