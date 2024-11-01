<?php

namespace Triangle\Customizer;

!defined( 'WPINC ' ) or die;

/**
 * Plugin hooks in a backend
 *
 * @package    Triangle
 * @subpackage Triangle/Controller
 */

class Ace extends \WP_Customize_Control {

    /**
     * Default class type from WP_Customize_Control
     */
    public $type = 'triangle_text';

    /**
     * @access   protected
     * @var      $view    Customizer View
     */
    protected $view;

    /**
     * Render the control's content.
     */
    public function render_content() {
        $this->view->setTemplate('backend.blank');
        $this->view->setSections(['Customizer.ace' => []]);
        $this->view->addData([ 'control' => $this ]);
        $this->view->build();
    }

    /**
     * @return
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * @param
     */
    public function setView($view)
    {
        $this->view = $view;
    }

}