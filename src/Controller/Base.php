<?php

namespace Triangle\Controller;

!defined( 'WPINC ' ) or die;

/**
 * Plugin hooks in a backend
 *
 * @package    Triangle
 * @subpackage Triangle/Controller
 */

use Triangle\View;

class Base extends Controller {

    /**
     * @backend - Load plugin libraries
     * @return  void
     * @var     array   $screens     Lists of screen where the library are loaded
     * @var     array   $types       Lists of post_type where the library are loaded
     * @var     array   $assets      Custom assets for specific page
     */
    protected function backend_load_plugin_libraries($screens = [], $types = [], $assets = []){
        $screen = unserialize(TRIANGLE_SCREEN);
        if(in_array($screen->base,$screens) || (isset($screen->post->post_type) && in_array($screen->post->post_type,$types)) ){
            $assets = ($assets) ? array_flip($assets) : $assets;
            /** Plugin configuration */
            if(!isset($assets['disableCore'])){
                $view = new View($this->Plugin);
                $view->setTemplate('backend.blank');
                $view->setSections(['Backend.script' => []]);
                $view->setOptions(['shortcode' => false]);
                $view->addData(['screen' => unserialize(TRIANGLE_SCREEN)]);
                $view->addData(['options' => [
                    'animation_tab' => $this->Service->Option->get_option('triangle_animation_tab'),
                    'animation_content' => $this->Service->Option->get_option('triangle_animation_content'),
                ]]);
                $view->build();
            }

            /** Styles and Scripts */
            $min = (TRIANGLE_PRODUCTION) ? '.min' : '';
            $this->Service->Asset->wp_enqueue_style('triangle_css', "backend/style$min.css" );
            $this->Service->Asset->wp_enqueue_script('triangle_js', "backend/plugin$min.js",'', '', true);
            /** Fontawesome */
            $this->Service->Asset->wp_enqueue_style('fontawesome_css', 'fontawesome/css/all.min.css');
            /** Animate.css */
            if($this->Service->Option->get_option('triangle_animation')) $this->Service->Asset->wp_enqueue_style('animatecss', 'animate.min.css');
            /** jQuery Select2 */
            $this->Service->Asset->wp_enqueue_style('select2css', 'select2.min.css');
            $this->Service->Asset->wp_enqueue_script('select2js', 'select2.full.min.js');
            /** Wordpress Cores */
            if(isset($assets['wp-tinymce'])) $this->Service->Asset->wp_enqueue_script('tinymce_js', $this->Service->Asset->includes_url( 'js/tinymce/' ) . 'wp-tinymce.php', array( 'jquery' ), false, true);
            if(isset($assets['colorpicker'])){
                $this->Service->Asset->wp_enqueue_style('colorpicker_classic_css', 'colorpicker/themes/classic.min.css');
                $this->Service->Asset->wp_enqueue_script('colorpicker_js', 'colorpicker/pickr.es5.min.js','','',false);
            }
            /** Confirm JS */
            if(isset($assets['confirm'])){
                $this->Service->Asset->wp_enqueue_style('confirm_css', 'jquery-confirm.min.css');
                $this->Service->Asset->wp_enqueue_script('confirm_js', 'jquery-confirm.min.js','','',true);
            }
            /** Ace JS - Code Editor */
            if(isset($assets['ace'])){
                $this->Service->Asset->wp_enqueue_script('acejs', 'ace/ace.min.js','','',true);
                $this->Service->Asset->wp_enqueue_script('acejs_search', 'ace/ext-searchbox.min.js','','',true);
                $this->Service->Asset->wp_enqueue_script('acejs_mode_html', 'ace/mode-html.min.js','','',true);
                $this->Service->Asset->wp_enqueue_script('acejs_emmet_core', 'ace/emmet.min.js','','',true);
                $this->Service->Asset->wp_enqueue_script('acejs_emmet', 'ace/ext-emmet.min.js','','',true);
            }
        }
    }

}