<?php

namespace Dot\Wordpress\Hook;

!defined( 'WPINC ' ) or die;

/**
 * Wordpress parent hook for (Action, Filter, and Shortcode)
 *
 * @package    Dot
 * @subpackage Dot\Includes\Wordpress
 */

abstract class Hook {

    /**
     * @access   protected
     * @var      array    $hook    The name of the WordPress hook that is being registered.
     */
    protected $hook;

    /**
     * @access   protected
     * @var      array    $component    A reference to the instance of the object on which the hook is defined.
     */
    protected $component;

    /**
     * @access   protected
     * @var      array    $callback    The name of the function definition on the $component.
     */
    protected $callback;

    /**
     * @access   protected
     * @var      array    $priority    The priority at which the function should be fired.
     */
    protected $priority;

    /**
     * @access   protected
     * @var      array    $accepted_args    The number of arguments that should be passed to the $callback.
     */
    protected $accepted_args;

    /**
     * Hook constructor
     * @return void
     */
    public function __construct(){
        $this->priority = 10;
        $this->accepted_args = 0;
    }

    /**
     * Method to run hook
     * @return  void
     */
    abstract function run();

    /**
     * @return string
     */
    public function getHook()
    {
        return $this->hook;
    }

    /**
     * @param string $hook
     */
    public function setHook($hook)
    {
        $this->hook = $hook;
    }

    /**
     * @return object
     */
    public function getComponent()
    {
        return $this->component;
    }

    /**
     * @param object $component
     */
    public function setComponent($component)
    {
        $this->component = $component;
    }

    /**
     * @return string
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * @param string $callback
     */
    public function setCallback($callback)
    {
        $this->callback = $callback;
    }

    /**
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param int $priority
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
    }

    /**
     * @return int
     */
    public function getAcceptedArgs()
    {
        return $this->accepted_args;
    }

    /**
     * @param int $accepted_args
     */
    public function setAcceptedArgs($accepted_args)
    {
        $this->accepted_args = $accepted_args;
    }

}