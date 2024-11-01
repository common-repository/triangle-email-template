<?php

namespace Dot\Wordpress;

!defined( 'WPINC ' ) or die;

/**
 * Add extra layer for wordpress functions
 *
 * @package    Dot
 * @subpackage Dot\Wordpress
 */

class Service {

    /**
     * Service constructor
     * @return void
     */
    public function __construct() {
        $this->API = new Service\API();
        $this->Asset = new Service\Asset();
        $this->Option = new Service\Option();
        $this->Page = new Service\Page();
        $this->Shortcode = new Service\Shortcode();
        $this->Template = new Service\Template();
        $this->Validate = new Service\Validate();
        $this->User = new Service\User();
    }

}