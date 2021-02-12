<?php
class SP_Framework_Woocommerce{

	static public function get_product_price($productID){
		if (class_exists('WC_Product')){
			$product = new WC_Product($productID);
			$price = $product->get_regular_price();
			return $price;
		}	
	}

	static public function get_product_sale_price($productID){
		if (class_exists('WC_Product')){
			$product = new WC_Product($productID);
			$price = $product->get_sale_price();
			return $price;
		}	
	}

	static public function get_product_gallery($productID){
		if (class_exists('WC_Product')){
			$product = new WC_Product($productID);
			$attachments = $product->get_gallery_image_ids();
			return $attachments;
		}	
	}

	static public function add_to_cart($productId){
		global $woocommerce;
    	$woocommerce->cart->add_to_cart($productId);
	}

	static public function get_cart_count(){
		$result = WC()->cart->get_cart_contents_count();
		return $result;			
	}

	static public function get_cart_url(){
		$result = wc_get_cart_url();
		return $result;	
	}

	static public function in_cart($productID){	
		global $woocommerce;

		foreach($woocommerce->cart->get_cart() as $key => $value ) {
	        
			if(isset($value['product_id']) && !empty($value['product_id'])){
		        if($productID == $value['product_id']){
		            return true;
		        }
		    } else {
		    	return false;
		    }    

	    }
	}

}