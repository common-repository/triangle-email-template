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
use Dot\Wordpress\Email;
use Dot\Wordpress\Hook\Action;
use Dot\Wordpress\Hook\Shortcode;

class EmailTemplate extends Base {

    /**
     * Admin constructor
     * @return void
     * @var    object   $plugin     Plugin configuration
     * @pattern prototype
     */
    public function __construct($plugin){
        parent::__construct($plugin);
        $this->loadModel('EmailTemplate');

        /** @backend - Eneque scripts */
        $action = new Action();
        $action->setComponent($this);
        $action->setHook('admin_enqueue_scripts');
        $action->setCallback('backend_enequeue');
        $action->setAcceptedArgs(1);
        $this->hooks[] = $action;

        /** @backend @emailTemplate - Setup builder,editor script */
        $action = clone $action;
        $action->setHook('edit_form_after_title');
        $action->setCallback('edit_emailtemplate');
        $action->setAcceptedArgs(0);
        $this->hooks[] = $action;

        /** @backend @emailTemplate - Setup editor script */
        $shortcode = new Shortcode();
        $shortcode->setComponent($this);
        $shortcode->setHook('triangle_send');
        $shortcode->setCallback('shortcode_send');
        $shortcode->setAcceptedArgs(2);
        $this->hooks[] = $shortcode;
    }

    /**
     * Eneque scripts @backend
     * @return  void
     * @var     array   $hook_suffix     The current admin page
     */
    public function backend_enequeue($hook_suffix){
        $screen = unserialize(TRIANGLE_SCREEN);
        $requiredAssets = [];
        /** Load Assets Libraries for pages and sections */
        if (in_array($screen->pagenow, ['post.php', 'post-new.php'])){
            if(isset($_GET['section']) && $_GET['section'] == 'codeeditor') { /** @emailtemplate @edit - @section codeeditor */
                $requiredAssets = ['ace'];
                $this->Service->Asset->wp_enqueue_script('emailtemplate_codeeditor_js', 'backend/emailtemplate/edit-codeeditor.js', [], false, true);
            } else { /** @emailtemplate @edit - @section builder */
                $this->Service->Asset->wp_enqueue_media();
                $this->Service->Asset->wp_enqueue_script('emailtemplate_builder_js', 'backend/emailtemplate/edit-builder.js', [], false, true);
                $this->Service->Asset->wp_enqueue_script('jquery_ui', 'backend/emailtemplate/jquery-ui.min.js', [], false, true);
                $requiredAssets = ['wp-tinymce','colorpicker','confirm'];
            }
        }
        $this->backend_load_plugin_libraries([], [$this->EmailTemplate->getName()], $requiredAssets);
    }

    /**
     * Setup scripts and metabox
     * @backend - @emailtemplate
     * @return  void
     */
    public function edit_emailtemplate(){
        $screen = unserialize(TRIANGLE_SCREEN);
        if(isset($screen->post->post_type) && $screen->post->post_type==$this->EmailTemplate->getName()){
            /** Prepare Data */
            $this->EmailTemplate->setID($screen->post->ID);
            $screen->post->template = $this->EmailTemplate->getMetas()['template_html'];
            $screen->post->template = $screen->post->template->get_post_meta(true);
            $options = [ 'builder_codeeditor' => $this->Service->Option->get_option('triangle_builder_codeeditor') ];

            /** Load Sections */
            $activeSection = (!isset($_GET['section'])) ? 'builder' : $_GET['section'];
            $urlCustomize = unserialize(TRIANGLE_PATH)['home_url'] . '?triangle_customize=true&post_id='. $screen->post->ID;
            $urlCustomize = unserialize(TRIANGLE_PATH)['admin_url'] . 'customize.php?url=' . urlencode($urlCustomize) . '&triangle_template=true&post_id=' . $screen->post->ID;
            $sections = array();
            $sections['EmailTemplate.edit-builder'] = ['name' => 'Builder', 'link' => 'builder'];
            if($options['builder_codeeditor']) $sections['EmailTemplate.edit-codeeditor'] = ['name' => 'Code editor', 'link' => 'codeeditor'];
            $sections['EmailTemplate.edit-customize'] = ['name' => 'Customize', 'link' => $urlCustomize];
            $sections['EmailTemplate.edit-' . $activeSection]['active'] = true;

            /** Redirect if section is not loaded */
            if(!isset($sections['EmailTemplate.edit-' . $activeSection]['name'])){
                $path = array();
                $path['post'] = $screen->post->ID;
                $path['action'] = 'edit';
                $path['section'] = 'builder';
                $path = unserialize(TRIANGLE_PATH)['admin_url'] . 'post.php?' . http_build_query($path);
                $this->Service->Page->js_redirect($path);
            }

            /** Setup View */
            $view = new View($this->Plugin);
            $view->setTemplate('backend.box');
            $view->setOptions(['shortcode' => false]);
            $view->addData([
                'disableTab'    => 'true',
                'screen'        => $screen,
                'background'    => 'bg-wetasphalt',
                'options'       => [
                    'triangle_builder_inliner' => $this->Service->Option->get_option('triangle_builder_inliner')
                ],
                'template'      => $screen->post->template,
            ]);
            $view->setSections($sections);
            $view->build();
        }
    }

    /**
     * Setup scripts and metabox
     * @backend - @emailtemplate
     * @return  void
     */
    public function loadTemplate($post_id){
        /** Prepare Data */
        $this->EmailTemplate->setID($post_id);
        $post = $this->EmailTemplate->get_post();
        $post->options = $this->EmailTemplate->getMetas()['template_options']->get_post_meta(true);
        $post->options = json_decode($post->options);
        $post->template = $this->EmailTemplate->getMetas()['template_html']->get_post_meta(true);
        $post->css = $this->EmailTemplate->getMetas()['template_css']->get_post_meta(true);

        /** Setup View */
        $view = new View($this->Plugin);
        $view->setTemplate('emailtemplate.default');
        $view->setSections(['EmailTemplate.email-content' => ['name' => 'Customize', 'active' => true]]);
        $view->setOptions(['shortcode' => true]);
        $view->addData([
            'post'      => $post,
            'options' => $post->options,
            'defaults'  => $this->Plugin->getConfig()->defaultSettings,
        ]);
        return $view;
    }

}