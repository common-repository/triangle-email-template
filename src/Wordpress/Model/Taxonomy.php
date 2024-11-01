<?php

namespace Dot\Wordpress\Model;

!defined( 'WPINC ' ) or die;

/**
 * Abstract class for wordpress model
 *
 * @package    Dot
 * @subpackage Dot\Includes\Wordpress
 */

class Taxonomy extends Model {

    /**
     * @access   protected
     * @var      array    $type    Post type for taxonomies
     */
    protected $type;

    /**
     * Method to model
     * @return void
     */
    public function build(){
        register_taxonomy($this->name, $this->type->getName() , $this->args);
    }

    /**
     * @return array
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param array $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

}