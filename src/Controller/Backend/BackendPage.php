<?php

namespace Triangle\Controller;

!defined( 'WPINC ' ) or die;

/**
 * Initiate plugins
 *
 * @package    Triangle
 * @subpackage Triangle/Controller
 */

use Triangle\View;
use Dot\Wordpress\Hook\Action;
use Dot\Wordpress\Page\MenuPage;
use Dot\Wordpress\Page\SubmenuPage;

class BackendPage extends Base {

    /**
     * Admin constructor
     * @return void
     * @var    object   $plugin     Plugin configuration
     * @pattern prototype
     */
    public function __construct($plugin){
        parent::__construct($plugin);

        /** @backend - Add contact page to send an email */
        $action = new Action();
        $action->setComponent($this);
        $action->setHook('admin_menu');
        $action->setCallback('page_contact');
        $this->hooks[] = $action;

        /** @backend - Add template submenu link for template cpt */
        $action = clone $action;
        $action->setHook('admin_menu');
        $action->setCallback('link_email_template');
        $this->hooks[] = $action;

        /** @backend - Add custom admin page under settings */
        $action = clone $action;
        $action->setHook('admin_menu');
        $action->setCallback('page_setting');
        $this->hooks[] = $action;
    }

    /**
     * Page Contact
     * @backend @submenu Triangle
     * @return  void
     */
    public function page_contact(){
        /** Validate Params */
        $default = array();
        if(isset($_POST['triangle_contact'])){
            $default = ['field_template', 'field_users', 'field_from_name', 'field_from_email', 'field_email_subject'];
            if(!$this->validateParams($_POST, $default)) die('Parameters did not match the specs!');
            $default = array_flip($default);
            foreach($default as &$value) $value = 'text';
        }
        /** Sanitize Params */
        $default['triangle_contact'] = 'key';
        $params = $this->sanitizeParams($_POST, $default);
        $params = array_merge($params, $this->sanitizeParams($_GET, ['user_id' => 'text']));

        /** Set View */
        $view = new View($this->Plugin);
        $view->setTemplate('backend.default');
        $view->setOptions(['shortcode' => true]);
        $view->addData(['background' => 'bg-carrot']);

        /** Handle submission */
        if($params['triangle_contact']=='send'){
            /** Prepare Data */
            $this->loadController('EmailTemplate');
            $template = $this->EmailTemplate->loadTemplate($params['field_template']);

            /** Setup View */
            $view->setSections(['Backend.contact.send' => ['name' => 'Send', 'active' => true]]);
            $view->addData([
                'title' => 'Send',
                'params' => $params,
                'template' => $template,
            ]);
        }
        else {
            $view->setSections(['Backend.contact.contact' => ['name' => 'Contact', 'active' => true]]);
            $view->addData([
                'title' => 'Contact User',
                'user_id' => $params['user_id'],
            ]);
        }

        /** Set Main Page */
        $menuSlug = strtolower(TRIANGLE_NAME);
        $page = new MenuPage();
        $page->setPageTitle(TRIANGLE_NAME);
        $page->setMenuTitle(TRIANGLE_NAME);
        $page->setCapability('manage_options');
        $page->setMenuSlug($menuSlug);
        $page->setIconUrl('dashicons-email');
        $page->setView($view);
        $page->setPosition(90);
        $page->build();

        /** Set Page */
        $menuSlug = strtolower(TRIANGLE_NAME);
        $page = new SubmenuPage();
        $page->setParentSlug(strtolower(TRIANGLE_NAME));
        $page->setMenuTitle('Contact');
        $page->setCapability('manage_options');
        $page->setFunction([$this, 'loadContent']);
        $page->setMenuSlug($menuSlug);
        $page->setView($view);
        $page->build();
    }

    /**
     * Page Setting
     * @backend @submenu setting
     * @return  void
     */
    public function page_setting(){
        /** Sanitize Params */
        $params = $this->sanitizeParams($_POST, ['field_menu_slug' => 'key']);

        /** Handle submission */
        $menuSlug = strtolower(TRIANGLE_NAME) . '-setting';
        if($params['field_menu_slug']=='triangle-setting'){
            $this->loadController('Backend');
            $result = $this->Backend->saveSettings();
            $result = ($result) ? 'true' : 'false';
        }

        /** Set View */
        $view = new View($this->Plugin);
        $view->setTemplate('backend.default');
        $view->setOptions(['shortcode' => false]);
        $view->addData([
            'menuSlug'      => $menuSlug,
            'background'    => 'bg-alizarin',
            'result'        => isset($result) ? $result : '',
            'options'       => [
                // Animation
                'triangle_animation' => $this->Service->Option->get_option('triangle_animation'),
                'triangle_animation_tab' => $this->Service->Option->get_option('triangle_animation_tab'),
                'triangle_animation_content' => $this->Service->Option->get_option('triangle_animation_content'),
                // Builder
                'triangle_builder_inliner' => $this->Service->Option->get_option('triangle_builder_inliner'),
                'triangle_builder_codeeditor' => $this->Service->Option->get_option('triangle_builder_codeeditor'),
                // SMTP
                'triangle_smtp' => $this->Service->Option->get_option('triangle_smtp'),
                'triangle_smtp_encryption' => $this->Service->Option->get_option('triangle_smtp_encryption'),
                'triangle_smtp_host' => $this->Service->Option->get_option('triangle_smtp_host'),
                'triangle_smtp_port' => $this->Service->Option->get_option('triangle_smtp_port'),
                'triangle_smtp_auth' => $this->Service->Option->get_option('triangle_smtp_auth'),
                'triangle_smtp_tls' => $this->Service->Option->get_option('triangle_smtp_tls'),
                'triangle_smtp_username' => $this->Service->Option->get_option('triangle_smtp_username'),
                'triangle_smtp_password' => md5($this->Service->Option->get_option('triangle_smtp_password')),
            ]
        ]);
        $view->setSections([
            'Backend.setting.setting' => ['name' => 'Setting', 'active' => true],
        ]);

        /** Set Page */
        $page = new SubmenuPage();
        $page->setParentSlug(strtolower(TRIANGLE_NAME));
        $page->setPageTitle(TRIANGLE_NAME);
        $page->setMenuTitle('Setting');
        $page->setCapability('manage_options');
        $page->setMenuSlug($menuSlug);
        $page->setView($view);
        $page->build();
    }

    /**
     * Page Contact
     * @backend @submenu Triangle
     * @return  void
     */
    public function link_email_template(){
        $page = new SubmenuPage();
        $page->setParentSlug(strtolower(TRIANGLE_NAME));
        $page->setPageTitle('Email Template');
        $page->setMenuTitle('Template');
        $page->setCapability('manage_options');
        $page->setMenuSlug('edit.php?post_type=emailtemplate');
        $page->setFunction([]);
        $page->build();
    }

}