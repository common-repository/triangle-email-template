<?php

namespace Triangle\Api;

!defined( 'WPINC ' ) or die;

/**
 * Initiate plugins
 *
 * @package    Triangle
 * @subpackage Triangle/Controller
 */

use Triangle\View;
use Dot\Wordpress\Email;
use Dot\Wordpress\Hook\Action;

class EmailTemplate extends Api {

    /**
     * User API constructor
     * @return void
     * @var    object   $plugin     Plugin configuration
     * @pattern prototype
     */
    public function __construct($plugin){
        parent::__construct($plugin);
        $this->loadModel('EmailTemplate');

        /** @backend - API - Page Contact */
        $action = new Action();
        $action->setComponent($this);
        $action->setHook('wp_ajax_triangle-page-contact');
        $action->setCallback('page_contact');
        $action->setAcceptedArgs(0);
        $this->hooks[] = $action;

        /** @backend @builder - API - Editor Grid Setting */
        $action = clone $action;
        $action->setHook('wp_ajax_triangle-builder-row-setting');
        $action->setCallback('builder_row_setting');
        $this->hooks[] = $action;

        /** @backend @builder - API - Editor Grid Setting */
        $action = clone $action;
        $action->setHook('wp_ajax_triangle-builder-element-setting');
        $action->setCallback('builder_element_setting');
        $this->hooks[] = $action;

        /** @backend @codeeditor - API - Editor Code Editor */
        $action = clone $action;
        $action->setHook('wp_ajax_triangle-section-codeeditor');
        $action->setCallback('codeeditor_section');
        $this->hooks[] = $action;

        /** @backend @customizer - API - Save data */
        $action = clone $action;
        $action->setHook('wp_ajax_triangle-customizer-save');
        $action->setCallback('customizer_save');
        $this->hooks[] = $action;

        /** @backend - API - Send Email */
        $action = clone $action;
        $action->setHook('wp_ajax_triangle-send');
        $action->setCallback('send_email');
        $this->hooks[] = $action;
    }

    /**
     * Get data for Contact page
     * @backend
     * @return  void
     */
    public function page_contact(){
        /** Validate Params */
        $default = ['typeArgs', 'userArgs'];
        if(!$this->validateParams($_POST, $default)) die('Parameters did not match the specs!');

        /** Get Template Data */
        $data = array();
        $this->EmailTemplate->setArgs($_POST['typeArgs']);
        $data['templates'] = [];
        foreach($this->EmailTemplate->get_posts() as $template){
            $this->EmailTemplate->setID($template->ID);
            $meta = $this->EmailTemplate->getMetas()['template_html']->get_post_meta();
            if($meta) $data['templates'][] = $template;
        }

        /** Get User Data */;
        $data['users'] = $this->Service->User->get_users($_POST['userArgs']);
        $data['currentUser'] = $this->Service->User->get_current_user();
        $data['defaultUser'] = $this->Service->User->get_user_by('ID',$_POST['user_id']);
        /** Get default user */
        $this->Service->API->wp_send_json($data);
    }

    /**
     * Get data for EmailTemplate codeeditor section
     * @backend
     * @return  void
     */
    public function codeeditor_section(){
        /** Validate Params */
        $default = ['args' => ['post_id', 'post_name']];
        if(!$this->validateParams($_POST, $default)) die('Parameters did not match the specs!');

        /** Load Data */
        $data = array();
        $this->EmailTemplate->setID($_POST['args']['post_id']);
        $data['templates'] = $this->get_template_elements_value($_POST['args']['post_id']);
        $data['options'] = ['inliner' => $this->Service->Option->get_option('triangle_builder_inliner')];
        $this->Service->API->wp_send_json((object) $data);
    }

    /**
     * Ajax - Load editor row setting
     * @backend
     * @return  void
     */
    public function builder_row_setting(){
        /** Load Page */
        ob_start();
        $view = new View($this->Plugin);
        $view->setTemplate('backend.jconfirm');
        $view->setOptions(['shortcode' => false]);
        $view->addData([
            'background'    => 'bg-amethyst',
        ]);
        $view->setSections([
            'EmailTemplate.element.row-setting' => ['name' => 'Setting', 'active' => true],
        ]);
        $view->build();
        $content = ob_get_clean();
        echo $content; exit;
    }

    /**
     * Ajax - Load editor element setting
     * @backend
     * @return  void
     */
    public function builder_element_setting(){
        /** Validate Params */
        $default = ['column'];
        if(!$this->validateParams($_POST, $default)) die('Parameters did not match the specs!');
        /** Sanitize Params */
        $default = array_flip($default);
        $default['column'] = 'text';
        $params = $this->sanitizeParams($_POST, $default);

        /** Load Page */
        ob_start();
            $view = new View($this->Plugin);
            $view->setTemplate('backend.jconfirm');
            $view->setOptions(['shortcode' => false]);
            $view->addData([
                'background'    => 'bg-amethyst',
                'column'        => $params['column'],
            ]);
            $view->setSections([
                'EmailTemplate.element.element-editor' => ['name' => 'Editor', 'active' => true],
                'EmailTemplate.element.element-setting' => ['name' => 'Setting'],
            ]);
            $view->build();
        $content = ob_get_clean();
        echo $content; exit;
    }

    /**
     * Save customizer setting
     */
    public function customizer_save(){
        /** Validate parameters */
        $default = ['setting_triangle_post_id'];
        if(!$this->validateParams($_POST, $default)) die('Parameters did not match the specs!');
        /** Sanitize Params */
        $default = array();
        foreach($_POST as $key => $value) $default[$key] = 'text';
        $params = $this->sanitizeParams($_POST, $default);
        $params['setting_triangle_css'] = $_POST['setting_triangle_css'];

        /** Prepare Data */
        $this->EmailTemplate->setID($params['setting_triangle_post_id']);
        $results = array();
        /** Save Option */
        $options = [
            'background'    => $params['setting_triangle_background'],
            'container'     => [
                'width'         => $params['setting_triangle_container_width'],
            ],
        ];
        $meta = $this->EmailTemplate->getMetas()['template_options'];
        $meta->setValue(json_encode($options));
        $results[] = $meta->update_post_meta();
        /** Save CSS */
        $meta = $this->EmailTemplate->getMetas()['template_css'];
        $meta->setValue($params['setting_triangle_css']);
        $results[] = $meta->update_post_meta();

        $this->Service->API->wp_send_json($params);
    }

    /**
     * Get EmailTemplate configuration and meta_fields data
     * @var         int         Post ID
     * @return      array       Configurations and meta_fields value
     */
    public function send_email(){
        /** Validate parameters */
        $default = ['template', 'users', 'from' => ['name', 'email'], 'subject'];
        if(!$this->validateParams($_POST, $default)) die('Parameters did not match the specs!');

        /** Sanitize Params */
        $default = ['name' => 'text','email' => 'text'];
        $params = $this->sanitizeParams($_POST['from'], $default);
        $params = array('from' => $params);
        $default = ['template' => 'html', 'users' => 'text', 'subject' => 'text'];
        $params = array_merge($params, $this->sanitizeParams($_POST, $default));

        /** Prepare Data */
        $users = explode(',',$params['users']);
        foreach($users as &$user) $user = $this->Service->User->get_user_by('ID', $user)->data->user_email;
        $params['template'] = str_replace('\"','"', $params['template']);

        /** Send Email */
        $email = new Email();
        $headers = $email->getHeaders();
        $headers[] = 'From: '.$params['from']['name'].' <'.$params['from']['email'].'> ';
        $email->setHeaders($headers);
        $email->setTo($users);
        $email->setSubject($params['subject']);
        $email->setMessage($params['template']);
        $status = $email->send();

        $this->Service->API->wp_send_json($status);
    }

    /**
     * Get EmailTemplate configuration and meta_fields data
     * @var         int         Post ID
     * @return      array       Configurations and meta_fields value
     */
    private function get_template_elements_value($postID){
        $templates = $this->Plugin->getConfig()->templates;
        $this->EmailTemplate->setID($postID);
        foreach($templates as $template){
            foreach($template->children as &$children){
                $children->value = $children->id;
                $children->value = $this->EmailTemplate->getMetas()["template_" . $children->id];
                $children->value = $children->value->get_post_meta();
            }
        }
        return $templates;
    }

}