<?php
class SP_Framework_Menu {

	public static function get( $menu_name ) {
		$locations = get_nav_menu_locations();
		$menu_list  = array();

		if ( $locations && isset( $locations[ $menu_name ] ) ) {
			$menu      = wp_get_nav_menu_object( $locations[ $menu_name ] );
			$menu_items = wp_get_nav_menu_items( $menu );

			foreach ( $menu_items as $key => $menu_item ) {

				$menu_list[ $menu_item->ID ]['id']            = $menu_item->ID;
				$menu_list[ $menu_item->ID ]['title']         = $menu_item->title;
				$menu_list[ $menu_item->ID ]['url']           = $menu_item->url;
				$menu_list[ $menu_item->ID ]['attr_title']    = $menu_item->attr_title;
				$menu_list[ $menu_item->ID ]['class']         = $menu_item->classes[0];
				$menu_list[ $menu_item->ID ]['parent']        = $menu_item->menu_item_parent;
				$menu_list[ $menu_item->ID ]['have_children'] = '';

			}
		}

		foreach ( $menu_list as $menu_item ) {
			if ( $menu_item['parent'] === 0 ) {
				$key = array_search( $menu_item['id'], array_column( $menu_list, 'parent' ) );
				if ( $key ) {
					$menu_list[ $menu_item['id'] ]['have_children'] = 'y';
				}
			}
		}

		return $menu_list;
	}

}
