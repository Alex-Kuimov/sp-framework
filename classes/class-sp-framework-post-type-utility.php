<?php
class SP_Framework_Post_Type_Utility{

	static public function get_list($data = null){
		//result array
		$result = array();
		
		//checking
		if($data){

			//default value
			$values = array(
				'numberposts' 		=> -1,
				'orderby'     		=> 'id',
				'order'       		=> 'DESC',
				'include'     		=> array(),
				'exclude'     		=> array(),
				'post_type'   		=> 'post',
				'post_status' 		=> 'publish',
				'suppress_filters' 	=> true,
				'meta_key'			=> '',
				'meta_value'		=> '',
				'tax_query' 		=> array(),
				'meta_query' 		=> array(),
				'posts_per_page'	=> '',
				'paged'				=> '',
				's'					=> '',
			); 

			//set custom value
			foreach ($values as $key => $value) {
				if (array_key_exists($key, $data)) {
					$values[$key] = $data[$key];
				}
			}

			//args array
			$args = array(
				'numberposts' 		=> $values['numberposts'],
				'orderby'     		=> $values['orderby'],
				'order'       		=> $values['order'],
				'include'     		=> $values['include'],
				'exclude'     		=> $values['exclude'],
				'post_type'   		=> $values['post_type'],
				'post_status'   	=> $values['post_status'],
				'suppress_filters' 	=> $values['suppress_filters'],
				'meta_key'			=> $values['meta_key'],
				'meta_value'		=> $values['meta_value'],
				'tax_query' 		=> $values['tax_query'],
				'meta_query' 		=> $values['meta_query'],
				'posts_per_page'	=> $values['posts_per_page'],
				'paged'				=> $values['paged'],
				's'					=> $values['s'],
			);

			//get posts by args
			$posts = get_posts($args);
			$counter = 0;
			foreach ($posts as $post) {
				//counter increment
				$counter++;

				//current post id
				$postID = $post->ID;

				//data
				$result[$postID]['cnt'] 	= $counter;
				$result[$postID]['id'] 		= $postID;
				$result[$postID]['title'] 	= get_the_title($postID);				
				$result[$postID]['url'] 	= get_permalink($postID);

			}
		}

		//return result
		return $result;
	}

	static public function get_content($postID = null){
		if(!empty($postID)){
			$content = get_post_field('post_content', $postID);
		} else {
			$content = '';
		}

		return $content;
	}

	static public function get_image($postID, $size){
		$imgID = get_post_thumbnail_id($postID);
		$image = wp_get_attachment_image_src($imgID, $size);

		if($image[0] == '') $result = plugins_url('../assets/img/none.png', __FILE__);
		else $result = esc_url($image[0]);
		
		return $result;
	}

	static public function get_meta($postID, $name){
		if($postID && $name){
			$name = 'sp_'.$name;
			$value = get_post_meta($postID, $name, true);
		} else {
			$value = '';
		}	

		return $value;
	}

	static public function update_meta($id, $name, $value){
		if($id && $name && $value){
			$name = 'sp_'.$name;
			update_post_meta($id, $name, $value);
		}	
	}

	static public function get_pagination($wp_query = null, $args = null){
		if(!empty($wp_query)){
	        
	        if(isset($wp_query->query['paged'])){
	            $currentPaged = $wp_query->query['paged'];
	        } else{
	            $currentPaged = 1;
	        }    

	        if(isset($args['posts_per_page'])){
	        	$postsPerPage = $args['posts_per_page'];
	        } else {
	        	$postsPerPage = get_option('posts_per_page');
	        }

	        if(isset($wp_query->queried_object->taxonomy)){
	        	$currentTermID		= $wp_query->queried_object->term_id;
	        	$currentTaxonomy 	= $wp_query->queried_object->taxonomy;
	        	$paginationLink 	= get_term_link($currentTermID, $currentTaxonomy);
	        	$totalPosts 		= $wp_query->found_posts;
	        } else {

	        	if(isset($args['count_posts'])){
	        		$totalPosts 	= $args['count_posts'];
	        	} else {
	        		$totalPosts 	= $wp_query->found_posts;
	        	}

	        	if(isset($args['page'])){
	        		$paginationLink 	= get_home_url().'/'.$args['page'].'/';
	        	} else {
	        		$paginationLink 	= get_home_url().'/';
	        	}
	        }
        
	        $paginationCount 	= ceil($totalPosts / $postsPerPage);

	        $paramGET = '';
	        if(isset($_GET) && !empty($_GET)){
	        	$arrayGET = $_GET;
	        	$paramGET .= '?';
	        	foreach ($arrayGET  as $key => $value) {
	        		$paramGET .= $key.'='.$value.'&';
	        	}
	        } 

	        if(isset($args['range'])){
	        	$range = (int)$args['range'] - 1;
	        	$rangeDefault = (int)$args['range'] - 1;
	        } else {
	        	$range = 5;
	        	$rangeDefault = 5;
	        }	

	        if(isset($args['start_link_title'])){
	        	if($currentPaged == 1){
	        		$startLink = '<span>'.$args['start_link_title'].'</span>';
	        	} else {	
	        		$startLink = '<a href="'.$paginationLink.''.$paramGET.'">'.$args['start_link_title'].'</a>';
	        	}
	        } else {
	        	$startLink = '';
	        } 	

	        if(isset($args['start_link_title'])){
	        	if($currentPaged == $paginationCount){
	        		$endLink = '<span>'.$args['end_link_title'].'</span>';
	        	} else {
	        		$endLink = '<a href="'.$paginationLink.'page/'.$paginationCount.'/'.$paramGET.'">'.$args['end_link_title'].'</a>';
	        	}
	        } else {
	        	$endLink = '';
	        }

	        $result = '';

	        if($totalPosts > $postsPerPage){

		        if(isset($args['wrapper_start'])){
		        	$result .= $args['wrapper_start'];
		        }

		        $result .= $startLink;

		        for ($i=1; $i <= $paginationCount; $i++) { 

		            if($currentPaged == 1){
		               $range = $rangeDefault + 1; 
		            }

		            if($i <= $range + $currentPaged - 1){

		                if($range + $currentPaged <= $paginationCount){

		                    if($i >= $currentPaged - 1){

		                        if($i == $currentPaged){
		                            $result .= '<span>'.$i.'</span>';
		                        } else {
		                            $result .= '<a href="'.$paginationLink.'page/'.$i.'/'.$paramGET.'">'.$i.'</a>';
		                        }   

		                    }
		                } else {

		                    if($i >= $paginationCount-$range){

		                        if($i == $currentPaged){
		                            $result .= '<span>'.$i.'</span>';
		                        } else {
		                            $result .= '<a href="'.$paginationLink.'page/'.$i.'/'.$paramGET.'">'.$i.'</a>';
		                        }  
		                    }     
		                }    
		            } 
		        }

		        $result .= $endLink; 

		        if(isset($args['total']) && isset($args['total']) == 'y'){

		        	if(isset($args['wrapper_total_start'])){
		        		$result .= $args['wrapper_total_start'];
		        	}	

		        	$result .= '<span>'.$currentPaged;

		        	if(isset($args['total_separator'])){
		        		$result .= $args['total_separator'];
		        	}

		        	$result .= $paginationCount.'</span>';
		        	
		        	if(isset($args['wrapper_total_end'])){
		        		$result .= $args['wrapper_total_end'];
		        	}	

		        }

		        if(isset($args['wrapper_end'])){
		        	$result .= $args['wrapper_end'];
		        }
		    }    

        	return $result;
        }
	}

}