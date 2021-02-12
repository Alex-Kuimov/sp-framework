<?php
class SP_Framework_Enqueue extends SP_Framework_Main{

	public $args = array();

    function __construct(){
        $this->init();
    }

    private function init(){
        $this->styles();
        $this->scripts();
        $this->url();
    }

    private function styles(){
        add_action('wp_enqueue_scripts', function(){
            wp_enqueue_style('theme-style', get_stylesheet_uri());       
        });

        add_action('wp_enqueue_scripts', function(){
            $args = $this->args;
            foreach ($args as $key => $value) {
                if($key == 'css'){
                    foreach ($value as $front){
                        wp_enqueue_style($front['name'], get_template_directory_uri().$front['path']);
                    }
                }
            }
        });
    }

    private function scripts(){
        add_action('wp_footer', function(){
            $args = $this->args;
            foreach ($args as $key => $value) {
                if($key == 'js'){
                    foreach ($value as $front){
                        wp_enqueue_script($front['name'], get_template_directory_uri().$front['path']);

                        if(isset($front['localize']) && $front['localize'] == 'y'){
                            wp_localize_script($front['name'], 'spJs', array('ajaxUrl' => admin_url('admin-ajax.php'), 'themeUrl' => get_template_directory_uri()));
                        }

                        if(isset($front['jquery']) && $front['jquery'] == 'y'){
                            wp_enqueue_script('jquery');
                        }   
                            
                    }   
                }
            }
        });
    }

    private function url(){
        add_action('wp_footer', function(){
            $args = $this->args;
            foreach ($args as $key => $value) {
                if($key == 'url'){
                    foreach ($value as $front){
                        echo '<script src="'.$front['path'].'"></script>';
                    }   
                }
            }

        });
    }

}