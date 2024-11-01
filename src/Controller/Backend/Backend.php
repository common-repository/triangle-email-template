<?php

namespace Triangle\Controller;

!defined( 'WPINC ' ) or die;

/**
 * Plugin hooks in a backend
 *
 * @package    Triangle
 * @subpackage Triangle/Controller
 */

use Dot\Wordpress\Hook\Action;

class Backend extends Base {

    /**
     * Admin constructor
     * @return void
     * @var    object   $plugin     Plugin configuration
     * @pattern prototype
     */
    public function __construct($plugin){
        parent::__construct($plugin);

        /** @backend - Eneque scripts */
        $action = new Action();
        $action->setComponent($this);
        $action->setHook('phpmailer_init');
        $action->setCallback('phpmailerConfig');
        $action->setAcceptedArgs(1);
        $this->hooks[] = $action;

        /** @backend - Eneque scripts */
        $action = clone $action;
        $action->setHook('admin_enqueue_scripts');
        $action->setCallback('backend_enequeue');
        $this->hooks[] = $action;

        /** @backend - Add setting link for plugin in plugins page */
        $pluginName = strtolower($plugin->getName());
        $action = clone $action;
        $action->setHook("plugin_action_links_$pluginName/$pluginName.php");
        $action->setCallback('backend_plugin_setting_link');
        $this->hooks[] = $action;
    }

    /**
     * Eneque scripts @backend
     * @return  void
     * @var     array|object   $phpmailer     PHPMailer configuration
     */
    public function phpmailerConfig($phpmailer){
        if($this->Service->Option->get_option('triangle_smtp')){
            $phpmailer = !is_object($phpmailer) ? (object) $phpmailer : $phpmailer;
            $phpmailer->Mailer     = 'smtp';
            $phpmailer->Host       = $this->Service->Option->get_option('triangle_smtp_host');
            $phpmailer->SMTPAuth   = ($this->Service->Option->get_option('triangle_smtp_auth')) ? true : false;
            $phpmailer->Port       = $this->Service->Option->get_option('triangle_smtp_port');
            $phpmailer->Username   = $this->Service->Option->get_option('triangle_smtp_username');
            $phpmailer->Password   = $this->Service->Option->get_option('triangle_smtp_password');
            if($this->Service->Option->get_option('triangle_smtp_tls') && $this->Service->Option->get_option('triangle_smtp_tls')!='None'){
                $phpmailer->SMTPSecure = $this->Service->Option->get_option('triangle_smtp_encryption');
            }
        }
    }

    /**
     * Eneque scripts @backend
     * @return  void
     * @var     array   $hook_suffix     The current admin page
     */
    public function backend_enequeue($hook_suffix){
        define('TRIANGLE_SCREEN', serialize($this->Service->Page->getScreen()));
        $screens = ['toplevel_page_triangle','triangle_page_triangle-setting'];
        $this->backend_load_plugin_libraries($screens);
        $this->backend_load_plugin_scripts();
    }

    /**
     * @backend - Load plugin scripts in a page
     * @return  void
     */
    private function backend_load_plugin_scripts(){
        $screen = unserialize(TRIANGLE_SCREEN);
        if($screen->base=='users') $this->Service->Asset->wp_enqueue_script('triangle_user_js', 'backend/user.js');
        if($screen->base=='toplevel_page_triangle') {
            $this->Service->Asset->wp_enqueue_script('triangle_contact_js', 'backend/contact.js', '', '', true);
            if($this->Service->Option->get_option('triangle_builder_inliner')=='juice'){
                $this->Service->Asset->wp_enqueue_script('juice_js', 'builder/juice.build.js', [], false, true);
            } else {
                $this->Service->Asset->wp_enqueue_script('juice_js', 'builder/none.build.js', [], false, true);
            }
        }
        if($screen->base=='triangle_page_triangle-setting') $this->Service->Asset->wp_enqueue_script('triangle_setting_js', 'backend/setting.js', '', '', true);
    }

    /**
     * Add setting link in plugin page
     * @backend
     * @return  array   $links     Combined links with the new added one
     * @var     array   $links     Plugin links
     */
    public function backend_plugin_setting_link($links){
        return array_merge($links, ['<a href="options-general.php?page=' . strtolower(TRIANGLE_NAME). '">Settings</a>']);
    }

    /**
     * Save given options to database
     * @backend - @pageSetting
     * @return  bool
     */
    public function saveSettings(){
        /** Sanitize Params */
        $fields = [
            'field_option_animation' => 'text',
            'field_option_animation_tab' => 'text',
            'field_option_animation_content' => 'text',
            'field_option_builder_inliner' => 'text',
            'field_option_builder_codeeditor' => 'text',
            'field_option_smtp' => 'text',
            'field_option_smtp_auth' => 'text',
            'field_option_smtp_host' => 'text',
            'field_option_smtp_port' => 'text',
            'field_option_smtp_username' => 'text',
            'field_option_smtp_password' => 'text',
            'field_option_smtp_encryption' => 'text',
            'field_option_smtp_tls' => 'text',
        ];
        $options = $this->sanitizeParams($_POST, $fields);

        /** SMTP password resuse if unchange */
        $password = $this->Service->Option->get_option('triangle_smtp_password');
        $options['field_option_smtp_password'] = ($options['field_option_smtp_password']==md5($password)) ?
            $password : $options['field_option_smtp_password'];

        /** Transform & save field key */
        unset($options['field_menu_slug']);
        foreach($options as $key => $value){
            unset($options[$key]);
            $key = str_replace('field_option','triangle',$key);
            $this->Service->Option->update_option($key, $value);
            $options[$key] = $value;
        }
        return $options;
    }

}