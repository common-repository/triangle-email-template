<?php

namespace Triangle\Customizer;

!defined( 'WPINC ' ) or die;

/**
 * Plugin hooks in a backend
 *
 * @package    Triangle
 * @subpackage Triangle/Controller
 */

class Range extends \WP_Customize_Control {

    /**
     * Default class type from WP_Customize_Control
     */
    public $type = 'triangle_range';

    /**
     * @access   protected
     * @var      object    $view    Customizer View
     */
    protected $view;

    /**
     * Render the control's content.
     */
    public function render_content() {
        $this->view->setTemplate('backend.blank');
        $this->view->setSections(['Customizer.range' => []]);
        $this->view->addData([ 'control' => $this ]);
        $this->view->build();
    }

    /**
     * @return object
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * @param object $view
     */
    public function setView($view)
    {
        $this->view = $view;
    }

}