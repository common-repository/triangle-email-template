<div class="grid">

    <!-- Element Attributes -->
    <div class="row section-title">
        <div class="col-sm-12">
            <h3>Element Attributes</h3>
        </div>
    </div>

    <div class="row section-fields">
        <div class="col-sm-2">
            <label>Id</label>
        </div>
        <div class="col-sm-5">
            <input type="text" id="element-id" placeholder="Element Id">
        </div>
    </div>

    <div class="row section-fields">
        <div class="col-sm-2">
            <label>Class</label>
        </div>
        <div class="col-sm-5">
            <input type="text" id="element-class" placeholder="Element Class">
        </div>
    </div>

    <!-- Element Style -->
    <div class="row section-title">
        <div class="col-sm-12">
            <h3>Style</h3>
        </div>
    </div>

    <div class="row section-fields">
        <div class="col-sm-2">
            <label>Column Size</label>
        </div>
        <div class="col-sm-5">
            <select id="grid-column-size">
                <?php for($i = 1; $i<=12; $i++): ?>
                    <option value="<?= $i ?>" <?= (isset($column) && $column==$i) ? 'selected' : '' ?>><?= $i ?> Column</option>
                <?php endfor; ?>
            </select>
            <p class="field-info">
                Grid column size 1-12
            </p>
        </div>
    </div>

    <div class="row section-fields">
        <div class="col-sm-2">
            <label>Margin</label>
        </div>
        <div class="col-sm-5">
            <a id="element-margin-linked"><i class="fas fa-link"></i></a>
            <input id="element-margin-top" class="element-margin" type="text" style="width:20%" placeholder="top">
            <input id="element-margin-right" class="element-margin" type="text" style="width:20%" placeholder="right">
            <input id="element-margin-bottom" class="element-margin" type="text" style="width:20%" placeholder="bottom">
            <input id="element-margin-left" class="element-margin" type="text" style="width:20%" placeholder="left">
        </div>
    </div>

    <div class="row section-fields">
        <div class="col-sm-2">
            <label>Padding</label>
        </div>
        <div class="col-sm-5">
            <a id="element-padding-linked"><i class="fas fa-link"></i></a>
            <input id="element-padding-top" class="element-padding" type="text" style="width:20%" placeholder="top">
            <input id="element-padding-right" class="element-padding" type="text" style="width:20%" placeholder="right">
            <input id="element-padding-bottom" class="element-padding" type="text" style="width:20%" placeholder="bottom">
            <input id="element-padding-left" class="element-padding" type="text" style="width:20%" placeholder="left">
        </div>
    </div>
</div>