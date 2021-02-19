<?php
class SP_Framework_Woocommerce {

	public static function get_product_price( $product_id ) {
		if ( class_exists( 'WC_Product' ) ) {
			$product = new WC_Product( $product_id );
			return $product->get_regular_price();
		}
	}

	public static function get_product_sale_price( $product_id ) {
		if ( class_exists( 'WC_Product' ) ) {
			$product = new WC_Product( $product_id );
			return $product->get_sale_price();
		}
	}

	public static function get_product_gallery( $product_id ) {
		if ( class_exists( 'WC_Product' ) ) {
			$product = new WC_Product( $product_id );
			return $product->get_gallery_image_ids();
		}
	}

	public static function add_to_cart( $product_id ) {
		global $woocommerce;
		$woocommerce->cart->add_to_cart( $product_id );
	}

	public static function get_cart_count() {
		return WC()->cart->get_cart_contents_count();
	}

	public static function get_cart_url() {
		return wc_get_cart_url();
	}

	public static function in_cart( $product_id ) {
		global $woocommerce;

		foreach ( $woocommerce->cart->get_cart() as $key => $value ) {

			if ( isset( $value['product_id'] ) && ! empty( $value['product_id'] ) ) {
				if ( $product_id === $value['product_id'] ) {
					return true;
				}
			} else {
				return false;
			}
		}
	}

}
