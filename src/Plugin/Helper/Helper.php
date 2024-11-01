<?php

namespace Triangle;

!defined( 'WPINC ' ) or die;

/**
 * Helper library for Triangle plugins
 *
 * @package    Triangle
 * @subpackage Triangle\Includes
 */

class Helper {

    /**
     * Helper constructor
     * @return void
     */
    public function __construct() {
        $this->Html = new Helper\Html();
        $this->Directory = new Helper\Directory();
    }

    /**
     * Debug script
     * @return void
     */
    public function debug($data){
        echo '<pre>'; var_dump($data);
    }

    /**
     * Define const which will be used within the plugin
     * @param   object   $plugin     Wordpress plugin object
     * @return void
     */
    public function defineConst($plugin){
        define('TRIANGLE_NAME', $plugin->getName());
        define('TRIANGLE_VERSION', $plugin->getVersion());
        define('TRIANGLE_PRODUCTION', $plugin->isProduction());
    }

    /**
     * Convert html relative path into absolute path
     * @var     string  $path   Wordpress base path
     * @var     string  $html   Html string
     * @return  void
     */
    public function convertImagesRelativetoAbsolutePath($path, $html){
        $pattern = "/<img([^>]*) " .
            "src=\"([^http|ftp|https][^\"]*)\"/";
        $replace = "<img\${1} src=\"" . $path . "\${2}\"";
        return preg_replace($pattern, $replace, $html);
    }

    /**
     * Extract templates from config files
     * @var     array   $config         Lists of config templates
     * @var     array   $templates      Lists of templates, to return
     */
    public function getTemplatesFromConfig($config, $templates = []){
        foreach($config as $template){
            foreach($template->children as $children){
                $templates[$children->id] = $children;
            }
        }
        return $templates;
    }

}