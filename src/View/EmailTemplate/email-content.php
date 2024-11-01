<?php

!defined( 'WPINC ' ) or die;

/**
 * Single Template for EmailTemplate CPT
 *
 * @package    Triangle
 * @subpackage Triangle/EmailTemplate
 */
?>

<div id="builder_dom">
    <?= $post->template ?>
</div>

<style>
    <?= file_get_contents(unserialize(TRIANGLE_PATH)['plugin_path'] . 'assets/css/emailtemplate/style.css') ?>
    body { background-color: <?= (isset($options->background)) ? $options->background : $defaults->background ?>; }
    #builder_dom { max-width: <?= (isset($options->container->width)) ? $options->container->width : $defaults->container->width ?>px; }
    <?= $post->css ?>
</style>