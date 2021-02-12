<?php
class SP_Framework_Menu{

	static public function get($menuName){
        $locations = get_nav_menu_locations();
        $menuList = array();

        if( $locations && isset($locations[$menuName])){
            $menu = wp_get_nav_menu_object($locations[$menuName]);
            $menuItems = wp_get_nav_menu_items($menu);

            $parentID = '';
            foreach ($menuItems as $key => $menuItem){
            	
        		$menuList[$menuItem->ID]['id'] = $menuItem->ID;
            	$menuList[$menuItem->ID]['title'] = $menuItem->title;
            	$menuList[$menuItem->ID]['url'] = $menuItem->url;
            	$menuList[$menuItem->ID]['attr_title'] = $menuItem->attr_title;
            	$menuList[$menuItem->ID]['class'] = $menuItem->classes[0];
            	$menuList[$menuItem->ID]['parent'] = $menuItem->menu_item_parent;
            	$menuList[$menuItem->ID]['have_children'] = '';

            }
        }

        foreach ($menuList as $menuItem) {
        	if($menuItem['parent'] == 0){
        		$key = array_search($menuItem['id'], array_column($menuList, 'parent'));
        		if($key){
        			$menuList[$menuItem['id']]['have_children'] = 'y';
        		}
        	}
        }

        return $menuList;
	}

}