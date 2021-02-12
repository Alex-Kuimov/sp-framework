<?php 
/*
Plugin Name: SP Framework
Text Domain: spf86
Domain Path: /languages
Description: <strong>Special Pack Framework</strong> - Feature set for fast website development
Version: 2.0.3
Author: Alex Kuimov
Author URI: https://sp-framework.ru
Plugin URI: https://sp-framework.ru
*/

//require traits
require_once(plugin_dir_path(__FILE__).'/traits/trait-sp-framework-meta-data-fields.php'); 

//require classes
require_once(plugin_dir_path(__FILE__).'/classes/class-sp-framework-init.php'); 
require_once(plugin_dir_path(__FILE__).'/classes/class-sp-framework-main.php'); 
require_once(plugin_dir_path(__FILE__).'/classes/class-sp-framework-enqueue.php'); 
require_once(plugin_dir_path(__FILE__).'/classes/class-sp-framework-post-type.php'); 
require_once(plugin_dir_path(__FILE__).'/classes/class-sp-framework-taxonomy.php');
require_once(plugin_dir_path(__FILE__).'/classes/class-sp-framework-customizer.php');
require_once(plugin_dir_path(__FILE__).'/classes/class-sp-framework-ajax.php');
require_once(plugin_dir_path(__FILE__).'/classes/class-sp-framework-post-type-meta-box.php');
require_once(plugin_dir_path(__FILE__).'/classes/class-sp-framework-taxonomy-meta-box.php');
require_once(plugin_dir_path(__FILE__).'/classes/class-sp-framework-user-meta-box.php');
require_once(plugin_dir_path(__FILE__).'/classes/class-sp-framework-admin-meta-box.php');
require_once(plugin_dir_path(__FILE__).'/classes/class-sp-framework-post-type-utility.php');
require_once(plugin_dir_path(__FILE__).'/classes/class-sp-framework-taxonomy-utility.php');
require_once(plugin_dir_path(__FILE__).'/classes/class-sp-framework-user-utility.php');
require_once(plugin_dir_path(__FILE__).'/classes/class-sp-framework-admin-utility.php');
require_once(plugin_dir_path(__FILE__).'/classes/class-sp-framework-mail.php');
require_once(plugin_dir_path(__FILE__).'/classes/class-sp-framework-woocommerce.php');
require_once(plugin_dir_path(__FILE__).'/classes/class-sp-framework-woocommerce-cf.php');
require_once(plugin_dir_path(__FILE__).'/classes/class-sp-framework-widget.php');
require_once(plugin_dir_path(__FILE__).'/classes/class-sp-framework-widget-area.php');
require_once(plugin_dir_path(__FILE__).'/classes/class-sp-framework-menu.php');