<?php

!defined( 'WPINC ' ) or die;

/**
 * Range customizer template
 *
 * @package    Triangle
 * @subpackage Triangle/EmailTemplate
 */

?>

<div class="triangle_ace">
    <span class="customize-control-title"><?= $this->esc( 'html', $control->label ); ?></span>
    <textarea
        id="<?= $this->esc( 'attr', $control->id ) ?>"
        class="triangle_ace"
        style="display:none;"
        <?php $control->link() ?>
    ><?= $this->esc( 'html', $control->value() ) ?></textarea>
    <div id="template-editor-<?= $this->esc( 'attr', $control->id ) ?>">
        <?= $this->esc( 'html', $control->value() ) ?>
    </div>
</div>

<script>
    /**
     * Init code editor
     * */
    if (typeof init_editor !== "undefined") {
        init_editor({
            id : `<?= $this->esc( 'attr', $control->id ) ?>`,
            mode : `<?= $mode ?>`,
        });
    }
</script>