<?php

namespace Dot\Wordpress\Service;

!defined( 'WPINC ' ) or die;

/**
 * Add extra layer for wordpress functions
 *
 * @package    Dot
 * @subpackage Dot\Wordpress
 */

use Dot\View;

class Page {

    /**
     * Wordpress - Retrieves a modified URL query string.
     * @var  string     $key    Either a query variable key, or an associative array of query variables.
     * @var  string     $value    Either a query variable value, or a URL to act upon.
     * @var  string     $url    A URL to act upon.
     */
    public function add_query_arg($key, $value = null, $url = null){
        return ($url) ? add_query_arg($key, $value, $url) : add_query_arg($key, $value);
    }

    /**
     * Wordpress redirect
     */
    public function wp_redirect($url){ wp_redirect($url); exit; }

    /**
     * Manuall Javascript Redirect
     */
    public function js_redirect($url){
        ob_start();
            $view = new View((object)array());
            $view->setTemplate('backend.blank');
            $view->setSections(['Element.redirect' => []]);
            $view->addData(['redirectUrl' => $url]);
            $view->build();
        echo ob_get_clean(); exit;
    }

    /**
     * Wordpress get screen
     */
    public function getScreen(){
        global $post, $pagenow;
        $screen = function_exists('get_current_screen') ?
            get_current_screen() : (object)[];
        $screen->post = $post;
        $screen->pagenow = $pagenow;
        return $screen;
    }

    /**
     * Whether the site is being previewed in the Customizer.
     */
    public function is_customize_preview(){ return is_customize_preview(); }

}