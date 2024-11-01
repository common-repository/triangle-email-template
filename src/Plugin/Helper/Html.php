<?php

namespace Triangle\Helper;

!defined( 'WPINC ' ) or die;

/**
 * Helper library for Triangle plugins
 *
 * @package    Triangle
 * @subpackage Triangle\Includes
 */

class Html {

    /**
     * Outputing assets
     * @var   string    $src        Full URL of the script, or path of the script relative to the WordPress root directory
     * @var   bool     $asny        Async
     */
    public function css($src){
        $path = unserialize(TRIANGLE_PATH)['plugin_url'] . 'assets/css/';
        if(!strpos($src, '//')) $src = $path . $src;
        echo "<link rel='stylesheet' type='text/css' href='$src' />\n";
    }

    /**
     * Outputing assets
     * @var   string    $src        Full URL of the script, or path of the script relative to the WordPress root directory
     * @var   bool     $asny        Async
     */
    public function script($src, $async = false){
        $path = unserialize(TRIANGLE_PATH)['plugin_url'] . 'assets/js/';
        if(!strpos($src, '//')) $src = $path . $src;
        $async = ($async) ? 'async' : '';
        echo "<script src='$src' $async></script>\n";
    }

}