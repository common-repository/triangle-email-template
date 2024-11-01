<div class="grid">

    <!-- Row Attributes -->
    <div class="row section-title">
        <div class="col-sm-12">
            <h3>Attributes</h3>
        </div>
    </div>

    <div class="row section-fields">
        <div class="col-sm-2">
            <label>Id</label>
        </div>
        <div class="col-sm-5">
            <input type="text" id="row-id" placeholder="Row Id">
        </div>
    </div>

    <div class="row section-fields">
        <div class="col-sm-2">
            <label>Class</label>
        </div>
        <div class="col-sm-5">
            <input type="text" id="row-class" placeholder="Row Class">
        </div>
    </div>

    <!-- Row Style -->
    <div class="row section-title">
        <div class="col-sm-12">
            <h3>Style</h3>
        </div>
    </div>

    <div class="row section-fields">
        <div class="col-sm-2">
            <label>Background</label>
        </div>
        <div class="col-sm-5">
            <div class="color-picker"></div>
            <p class="field-info">
                Grid background color
            </p>
        </div>
    </div>

    <div class="row section-fields">
        <div class="col-sm-2">
            <label>Margin</label>
        </div>
        <div class="col-sm-5">
            <a id="row-margin-linked"><i class="fas fa-link"></i></a>
            <input id="row-margin-top" class="row-margin" type="text" style="width:20%" placeholder="top">
            <input id="row-margin-right" class="row-margin" type="text" style="width:20%" placeholder="right">
            <input id="row-margin-bottom" class="row-margin" type="text" style="width:20%" placeholder="bottom">
            <input id="row-margin-left" class="row-margin" type="text" style="width:20%" placeholder="left">
        </div>
    </div>

    <div class="row section-fields">
        <div class="col-sm-2">
            <label>Padding</label>
        </div>
        <div class="col-sm-5">
            <a id="row-padding-linked"><i class="fas fa-link"></i></a>
            <input id="row-padding-top" class="row-padding" type="text" style="width:20%" placeholder="top">
            <input id="row-padding-right" class="row-padding" type="text" style="width:20%" placeholder="right">
            <input id="row-padding-bottom" class="row-padding" type="text" style="width:20%" placeholder="bottom">
            <input id="row-padding-left" class="row-padding" type="text" style="width:20%" placeholder="left">
            <p class="field-info">
                Please note that the row padding might not applied due to the use of the grid.
                But your setting are still gonna be saved into the template.
            </p>
        </div>
    </div>
</div>