<?php

!defined( 'WPINC ' ) or die;

/**
 * Range customizer template
 *
 * @package    Triangle
 * @subpackage Triangle/EmailTemplate
 */

/** Set Default Value */
$range = (isset($range)) ? $range : array();
$range['min'] = (isset($range['min'])) ? $range['min'] : 0;
$range['max'] = (isset($range['max'])) ? $range['max'] : 100;
$range['step'] = (isset($range['step'])) ? $range['step'] : 1;

?>
<div class="triangle_range">
    <span class="customize-control-title"><?= $this->esc( 'html', $control->label ); ?></span>
    <div class="triangle_range_value"><?= $this->esc( 'html', $control->value() ) ?></div>
    <input type="range"
           id="<?= $this->esc( 'attr', $control->id ) ?>"
           class="triangle_range"
           min="<?= $range['min'] ?>"
           max="<?= $range['max'] ?>"
           step="<?= $range['step'] ?>"
           value="<?= $this->esc( 'html',  $control->value() ); ?>"
           <?php $control->link() ?>
    />
    <?php if ( ! empty( $control->description ) ) : ?>
        <p><span><?= $control->description; ?></span></p>
    <?php endif; ?>
</div>