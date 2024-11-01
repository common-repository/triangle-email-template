<div class="builder-container">
    <div id="builder_dom" class="grid email-grid">
        <?php if(trim($template)==''){ ?>
            <div class="row ui-sortable-handle">
                <div class="row-content ui-sortable" data-margin-linked="true" data-padding-linked="true">
                    <div class="element col-sm-12 ui-sortable-handle">
                        <div class="element-content" data-margin-linked="true" data-padding-linked="true" style="padding: 20px 0;"><p style="text-align:center;">Hello Triangle!</p></div>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <?= $template ?>
        <?php } ?>
    </div>
</div>

<!--Start : Builder Elements-->
<div style="display:none;">
    <div id="rendered_dom"></div>
    <textarea id="template_html" name="template_html" cols="30" rows="10"></textarea>
    <div id="row-setting" class="control-setting">
        <div class="row-header">
            <a id="row-action-move" title="Move Row"><i class="fas fa-arrows-alt"></i></a>
            <a id="row-action-clone" title="Clone Row"><i class="far fa-clone"></i></a>
            <a id="row-action-setting" title="Row Setting"><i class="fas fa-cog"></i></a>
            <a id="row-action-remove" title="Remove Row"><i class="fas fa-trash"></i></a>
        </div>
    </div>
    <div id="element-setting" class="control-setting">
        <a id="element-action-clone" title="Clone Element"><i class="far fa-clone"></i></a>
        <a id="element-action-setting" title="Element Setting"><i class="fas fa-cog"></i></a>
        <a id="element-action-remove" title="Remove Element"><i class="fas fa-trash"></i></a>
    </div>
    <div id="new-row">
        <div class="row-content ui-sortable" data-margin-linked="true" data-padding-linked="true">
            <div class="element col-sm-12 ui-sortable-handle">
                <div class="element-content" data-margin-linked="true" data-padding-linked="true" style="padding: 20px 0;"><p style="text-align:center;">Hello Triangle!</p></div>
            </div>
        </div>
    </div>
</div>
<!--End : Builder Element-->

<!--Start: FAB New Element-->
<div id="btn-add-new-element" class="fab bg-amethyst">
    <i class="fas fa-plus"></i>
</div>
<!--End: FAB New Element-->